<?php

namespace App\Http\Controllers;

use App\Event;
use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Traits\NotificationTrait;
use App\Image;
use App\Post;
use App\Report;
use App\Notification;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\ParameterBag;

class PostController extends Controller{

    use NotificationTrait;

    public function delete(Request $request, $id)
    {
      $post = Post::find($id);
      if(!isset($post))
        throw new HttpException(404, "post");

      $this->authorize('delete', $post);
      $post->delete();

      return $post;
    }

    public function archive(Request $request, $id)
    {
      $post = Post::find($id);
      if(!isset($post))
        throw new HttpException(404, "post");

      $this->authorize('archive', $post);

      $post->update(['type' => 'archived']);
      $post->save();

      return $post;
    }

    public function unarchive(Request $request, $id)
    {
      $post = Post::find($id);
      if(!isset($post))
        throw new HttpException(404, "post");

      $this->authorize('archive', $post);
      
      $report = Report::where([['reported_post_id',$id],['approval',true]])->get();
      if(count($report) > 0)
        $post->update(['type' => 'blocked']);
      else
        $post->update(['type' => 'normal']);
      $post->save();

      return $post;
    }

    /**
     * Generic create post function
     */
    public function createPost(Request $request,$group_id,$event_id)    {
      $post = new Post();

      $this->authorize('create', $post);

      $request->validate([
        'title' => "required|string|regex:/^[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i|max:255",
        'body' => "required|string|regex:/^[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i|max:255",
      ]);
      $post->title = $request->input('title');
      $post->body = $request->input('body');
      $post->author_id = Auth::user()->userable->regular_user_id; //TODO Change this to the id of the regular_user
      if($group_id){
        $post->group_id = $group_id;
        $interested = DB::table('user_in_group')
                      ->where([['group_id', '=', $group_id],['user_id','<>',Auth::user()->userable->regular_user_id]])
                      ->select('user_id')->get();
        
        $notification =  new Notification;
        $notification->origin_user_id = Auth::user()->userable->regular_user_id;
        $notification->notification_user_id = Auth::user()->userable->regular_user_id ;
        $notification->notification_group_id = $group_id;
        $notification->description = $notification->getDescription(" has a new post");
        $notification->link = $notification->link();
        $notification->save();
        //$this->sendNotifications($notification,$interested);Auth::user()->userable->image()
        $this->sendNotifications($notification,Auth::user()->userable->image(),$interested);
      }
      else if($event_id){
        $post->event_id = $event_id;
        $interested = DB::table('user_interested_in_event')
                      ->where([['event_id', '=', $event_id],['user_id','<>',Auth::user()->userable->regular_user_id]])
                      ->select('user_id')->get();
        $notification =  new Notification;
        $notification->origin_user_id = Auth::user()->userable->regular_user_id;
        $notification->notification_user_id = Auth::user()->userable->regular_user_id ;
        $notification->notification_event_id = $event_id;
        $notification->description = $notification->getDescription(" has a new post");
        $notification->link = $notification->link();
        $notification->save();
        //$this->sendNotifications($notification,$interested);
        $this->sendNotifications($notification,Auth::user()->userable->image(),$interested);
      }
      $post->save();
      $image = $this->upload_image($request, $post->post_id);

      $file = $this->upload_file($request, $post->post_id);
      
      $new_post = Post::take(1)
                        ->where("post_id", '=', $post["post_id"])
                        ->get(); 

        
      return view('partials.post',['post' => $new_post[0], 'image' => $image, 'file' => $file]); 
    }

    public function upload_file(Request $request, $post_id){
      $array = $request->validate([
        'file' => 'max:2048',
      ]);
    
      if(count($array) === 0)
        return null;

      $fileName = time().'.'.request()->file->getClientOriginalExtension();
  
      request()->file->move(public_path('files/posts'), $fileName);

      try{
        $file = File::where('post_id', '=', $post_id)->get()[0];
      }catch(Exception $e){
        $file = null;
      }

      if(isset($file) && $file !== null){ // Delete file 
        $file->delete();
      }
      
      $file = new File();
      $file->post_id = $post_id;
      $file->file_path = '/files/posts/' . $fileName;
      $file->save();

      return $file;
    }

    public function upload_image(Request $request, $post_id){
      $array = $request->validate([
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
      ]);
    
      if(count($array) === 0)
        return null;

      $imageName = time().'.'.request()->image->getClientOriginalExtension();
  
      request()->image->move(public_path('images/posts'), $imageName);

      try{
        $image = Image::where('post_id', '=', $post_id)->get()[0];
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
      $file->file_path = '/images/posts/' . $imageName;
      $file->save();

      $image = new Image();
      $image->file_id = $file->file_id;
      $image->post_id = $post_id;
      $image->save();

      return $image;
    }

    /**
     * Shows the post page
     */
    public function show($post_id){
      if (!Auth::check()) return redirect('/login');

      $post = Post::find($post_id);
      if(!isset($post))
        throw new HttpException(404, "post");

      if($post->type == 'blocked' && !Auth::user()->isAdmin())
        throw new HttpException(404, "post");
      else if($post->type == 'archived' && (!Auth::user()->isAdmin() || $post->author_id != Auth::user()->userable->regular_user_id))
        throw new HttpException(404, "post");
        
      return view('pages.post' , ['css' => ['navbar.css','posts.css'],
      'js' => ['general.js','post.js'], 'post' => $post, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
    }

    /**
     * Shows the post edit page
     */
    public function show_edit($post_id){
      if(!Auth::check()) return redirect('/login');
      
      $post = Post::find($post_id);
      if(!isset($post))
        throw new HttpException(404, "post");

      if($post->type == 'blocked' && !Auth::user()->isAdmin())
        throw new HttpException(404, "post");

      $this->authorize('edit', $post);

      return view('pages.post_edit' , ['css' => ['navbar.css','posts.css'],
      'js' => ['general.js','post.js'] , 'post' => $post, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
    }

     /**
     * Edits the post
     */
    public function edit(Request $request, $post_id){
      if(!Auth::check()) return redirect('/login');

      $post = Post::find($post_id); 
      if(!isset($post))
        throw new HttpException(404, "post");

      $this->authorize('edit', $post);

      $request->validate([
        'title' => "required|string|regex:/^[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i|max:255",
        'body' => "required|string|regex:/^[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i|max:255",
      ]);

      $title = $request->input('title');
      $body = $request->input('body');

      $post->update(['title' => $title, 'body' => $body]);

      Session::flash("success_message", "Post updated successfully.");

      return PostController::show($post_id);
    }

    /**
     * Creates a new post.
     *
     * @return Post The post created.
     */
    public function create(Request $request)
    {
      return $this->createPost($request,null,null);
    }

    /**
     * Creates a new post in a group.
     *
     * @return Post The post created.
     */
    public function createInGroup(Request $request, $id)
    {
      return $this->createPost($request,$id,null);
    }

    /**
     * Creates a new post in a event.
     *
     * @return Post The post created.
     */
    public function createInEvent(Request $request, $id)
    {
      return $this->createPost($request,null,$id);
    }

    /**
     * Report a post.
     *
     * @return Post The post created.
     */
    public function report(Request $request, $id)
    { 

      $request->validate([
        'title' => 'required|string|regex:/^[a-z0-9çáàãâéêíóõôú]+(?:[a-z0-9çáàãâéêíóõôú ]*[a-z0-9çáàãâéêíóõôú])?$/i|max:255',
        'description' => "required|string|regex:/^[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9çáàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i|max:2048",
      ]);

      $title = $request->input('title');
      $description = $request->input('description');
      $reporter_id = Auth::user()->userable->regular_user_id;

      $report = new Report();
      $report->title = $title;
      $report->reason = $description;
      $report->reporter_id = $reporter_id;
      $report->reported_post_id = $id;

      $report->save();
      return $report;
    }


    public function like(Request $request, $id , $val)
    {
      $post = Post::find($id);
      if(!isset($post))
        throw new HttpException(404, "post");
      //TODO: AUTHORIZE  
      $change = ['post_id' => $id ,'upvotes' => 0, 'downvotes' => 0];

      $like = $post->userLikes()->wherePivot('user_id' , Auth::user()->userable->regular_user_id)->first();
      if(!$like){
        DB::table('user_reaction')
              ->insert(['user_id' => Auth::user()->userable->regular_user_id,
                'post_id' => $id,
                'like_or_dislike' => $val]);
        if($val == 0){
          $post->increment('downvotes');
          $change['downvotes'] = 1;
        }else{
          $post->increment('upvotes');
          $change['upvotes'] = 1;
        }
      }else{ //se o like ja existir
        if($like->pivot->like_or_dislike == $val){ //se o valor for igual então tirar o like
          $like->pivot->delete();
          if($val == 0){
            $post->decrement('downvotes');
            $change['downvotes'] = -1;
          }else{
            $post->decrement('upvotes');
            $change['upvotes'] = -1;
          }
        }else{ //mudar o like
          $like->pivot->update(['like_or_dislike' => $val]);
          if($val == 0){
            $post->increment('downvotes'); $post->decrement('upvotes');
            $change = ['post_id' => $id ,'upvotes' => -1, 'downvotes' => 1];
          }else{
            $post->increment('upvotes'); $post->decrement('downvotes');
            $change = ['post_id' => $id ,'upvotes' => 1, 'downvotes' => -1];
          }
        }
      }

      return $change;
    }

}
/*
class SendMultipleNotifications extends Thread {

  use NotificationTrait;

  public function __construct($notification, $dest) {
      $this->notification = $notification;
      $this->dest = $dest;
  }

  public function run() {
    $this->sendNotifications($this->notification,$this->dest);
  }
}*/