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
use Exception;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Session;

class GroupController extends Controller{

    public function show($id){
      if (!Auth::check()) return redirect('/login');

      $group = Group::find($id);
      if(!isset($group))
        throw new HttpException(404, "group");

      $this->authorize('show', $group);

      $posts = $group->posts;

      $members = $group->members();

      $owner = DB::table('user_in_group')
            ->where('group_id', '=', $id)
            ->where('user_id', '=', Auth::user()->userable->regular_user_id)
            ->select('admin')
            ->limit(1);
      
      $image = $group->image();

      return view('pages.group' , ['is_admin' => false, 'notifications' => Auth::user()->userable->notifications, 'group' => $group, 'posts' => $posts, 'members' => $members, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'is_owner' => $owner , 'image' => $image]);
    }

    public function showCreateForm(){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Group');

      return view('pages.create_group', ['is_admin' => false, 'notifications' => Auth::user()->userable->notifications, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
    }

    public function create(Request $request){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Group');
      
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
          ]);
          $imageName = time().'.'.request()->image->getClientOriginalExtension();

          request()->image->move(public_path('images/groups'), $imageName);

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

      return view('pages.edit_group' , ['is_admin' => false, 'notifications' => Auth::user()->userable->notifications, 'group' => $group, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'image' => $image ]);
    }

    /**
     * Edits the group
     */
    public function edit(Request $request, $id){
      $group = Group::find($id);

      if(!isset($group))
        throw new HttpException(404, "group");

      $this->authorize('edit', $group);

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
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
}