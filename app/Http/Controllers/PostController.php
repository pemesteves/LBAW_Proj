<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Report;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Session;


class PostController extends Controller{

    public function delete(Request $request, $id)
    {
      $post = Post::find($id);
      if(!isset($post))
        throw new HttpException(404, "post");

      $this->authorize('delete', $post);
      $post->delete();

      return $post;
    }

    /**
     * Generic create post function
     */
    public function createPost(Request $request,$group_id,$event_id)    {
      $post = new Post();

      $this->authorize('create', $post);

      $request->validate([
        'title' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
        'body' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
      ]);

      $post->title = $request->input('title');
      $post->body = $request->input('body');
      $post->author_id = Auth::user()->userable->regular_user_id; //TODO Change this to the id of the regular_user
      if($group_id) $post->group_id = $group_id;
      if($event_id) $post->event_id = $event_id;
      $post->save();
      
      //Gets useful information about the post
      $new_post = Post::take(1)->where("post_id", '=', $post["post_id"])->get(); 
    
      return view('partials.post',['post' => $new_post[0]]); 
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
        
      return view('pages.post' , ['is_admin' => false , 'post' => $post, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
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

      return view('pages.post_edit' , ['is_admin' => false , 'post' => $post, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
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
        'title' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
        'body' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
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
        'title' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú]$/i|max:255',
        'description' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
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