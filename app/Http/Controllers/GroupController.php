<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Group;
use App\Image;
use App\Report;
use App\RegularUser;
use Exception;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Session;

class GroupController extends Controller{

    /**
     * Show group, dont show posts, if not part of group
     */

    public function show($id){
      if (!Auth::check()) return redirect('/login');

      $group = Group::find($id);
      if(!isset($group))
        throw new HttpException(404, "group");

      if($group->type == 'blocked' && !Auth::user()->isAdmin())
        throw new HttpException(404, "group");

      //$this->authorize('show', $group);

      $posts = null;
      if(Auth::user()->isAdmin() || Auth::user()->userable->groups->find($group) != null)
        $posts = $group->posts;

      $members = $group->members();
      $member_count = $group->member_count();

      $owner = DB::table('user_in_group')
            ->where('group_id', '=', $id)
            ->where('user_id', '=', Auth::user()->userable->regular_user_id)
            ->select('admin')
            ->limit(1)->get();
      
      $image = $group->image();


      return view('pages.group' , ['css' => ['navbar.css','group.css','posts.css','post_form.css','feed.css'], 
      'js' => ['general.js','group.js','infinite_scroll.js','post.js', 'uploadImages.js'], 'in_group' => true,
      'group' => $group, 'posts' => $posts, 'members' => $members, 'member_count' => $member_count, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'is_owner' => $owner , 'image' => $image]);
    }

    public function showCreateForm(){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Group');

      return view('pages.create_group', ['css' => ['navbar.css','group.css','posts.css','post_form.css','feed.css','help.css','create.css'],
      'js' => ['general.js','uploadImages.js'],
       'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
    }

    public function create(Request $request){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Group');

      $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        'name' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+(?:[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú])?$/i|max:255',
        'information' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i|max:255",
      ]);
      
      $group = DB::transaction(function() use($request){
        $group = new Group();
        $group->name = Input::get('name');
        $group->information = Input::get('information');
        $group->save();

        DB::table('user_in_group')->insert([
              'user_id' => Auth::user()->userable_id,
              'group_id' => $group->group_id,
              'admin' => true
            ]);

        if(request()->image !== null){
          $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:20048',
          ]);
          $imageName = time().'.'.request()->image->getClientOriginalExtension();

          request()->image->move(public_path('images/groups'), $imageName);
          https://manjaro.org/
          $file = new File();
          $file->file_path = '/images/groups/' . $imageName;
          $file->save();

          $image = new Image();
          $image->file_id = $file->file_id;
          $image->group_id = $group->group_id;
          $image->save();
        }
            
        return $group;
      });
    
      return redirect()->route('groups.show', $group);
    }

    public function show_edit($id){
      if(!Auth::check()) return redirect('/login');

      $group = Group::find($id);
      if(!isset($group))
        throw new HttpException(404, "group");

      $this->authorize('edit', $group);
      
      $image = $group->image();

      return view('pages.edit_group' , ['css' => ['navbar.css','group.css','posts.css','post_form.css','feed.css','help.css', 'create.css'],
      'js' => ['general.js','uploadImages.js'],
       'group' => $group, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'image' => $image ]);
    }

    /**
     * Edits the group
     */
    public function edit(Request $request, $id){
      $group = Group::find($id);

      if(!isset($group))
        throw new HttpException(404, "group");

      if($group->type == 'blocked' && !Auth::user()->isAdmin())
        throw new HttpException(404, "group");

      $this->authorize('edit', $group);

      $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        'name' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+(?:[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú])?$/i|max:255',
        'information' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i|max:255",
      ]);

      $name = $request->input('name');
      $information = $request->input('information');

      if(request()->image !== null)
        $this->upload_image($request, $id);

      $group->update(['name' => $name, 'information' => $information]);

      Session::flash("success_message", "Group updated successfully.");

      return GroupController::show($group->group_id);
    }

    /**
     * Report a group.
     *
     * @return Group The group reported.
     */
    public function report(Request $request, $id)
    { 

      $request->validate([
        'title' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+(?:[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú])?$/i|max:255',
        'description' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i|max:255",
      ]);

      $title = $request->input('title');
      $description = $request->input('description');
      $reporter_id = Auth::user()->userable->regular_user_id;

      $report = new Report();
      $report->title = $title;
      $report->reason = $description;
      $report->reporter_id = $reporter_id;
      $report->reported_group_id = $id;

      $report->save();
      return $report;
    }

    public function upload_image(Request $request, $group_id){
      $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
      ]);
      $imageName = time().'.'.request()->image->getClientOriginalExtension();

      request()->image->move(public_path('images/groups'), $imageName);

      try{
        $image = Image::where('group_id', '=', $group_id)->get()[0];
      }catch(Exception $e){
        $image = null;
      }

      if(isset($image) && $image !== null){ // Delete image and file
        $file_id = $image->file_id;
        $image->delete();
        
        $file = File::find($file_id);
        $file->delete();
      }
      
      $file = new File();
      $file->file_path = '/images/groups/' . $imageName;
      $file->save();

      $image = new Image();
      $image->file_id = $file->file_id;
      $image->group_id = $group_id;
      $image->save();

      return $image;
    }

    public function removeMember(Request $request, $group_id, $user_id){

      $member = DB::table('user_in_group')->where('group_id', $group_id)->where('user_id', $user_id)->first();

      DB::table('user_in_group')->where('group_id', $group_id)->where('user_id', $user_id)->delete();

      return json_encode($member);
      
    }

    function getPosts($group_id,$last_id){

      $group = Group::find($group_id);

      $this->authorize('show', $group);

      $myGroupsPosts = Post::join('user_in_group' , "post.group_id" , "user_in_group.group_id")
                                    ->where([['user_in_group.user_id', Auth::user()->userable->regular_user_id],['post.type','normal']])
                                    ->where([['post_id','<',$last_id],['post.group_id',$group_id]])
                                    ->select("post.*")
                                    ->orderBy('date','desc')->limit(3)->get();

      return view('requests.posts',['posts' => $myGroupsPosts]);
    }
    /**
     * Get friends to add to group, that dont belogn to group yet
     */
    public function getFriends(Request $request,$group_id){
      $str = strtolower($request->input('string'));
      $suggestions = RegularUser::join('user','regular_user.user_id','user.user_id')->join('friend','friend_id1','regular_user_id')
              ->where([['friend_id2',Auth::user()->userable->regular_user_id],['friend.type','accepted']])
              ->whereRaw('lower(name) LIKE \'%'.$str.'%\'')
              ->leftjoin('image','image.regular_user_id', '=', 'regular_user.regular_user_id')
              ->leftjoin('file', 'file.file_id', '=', 'image.file_id')
              ->whereNOTIn('regular_user.regular_user_id',function($query) use ($group_id){
                $query->select('user_id')->from('user_in_group')
                ->where([['group_id',$group_id]]);
              })
              ->get();
      return ['str' => $str , 'new_members' => $suggestions];
  }

    public function addToGroup($group_id,$user_id){
      $group = Group::find($group_id);
      //$this->authorize('add', $group);
      DB::table('user_in_group')->insert(['user_id' => $user_id,'group_id' => $group_id]);
      $member = DB::table('user')->join('regular_user', 'user.user_id', '=', 'regular_user.user_id')->where('regular_user_id', $user_id)-> first();
      return json_encode($member);
    }
}