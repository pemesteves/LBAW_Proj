<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use Exception;

class PostController extends Controller{

    public function delete(Request $request, $id)
    {
      $post = Post::find($id);

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

      $post->title = $request->input('title');
      $post->body = $request->input('body');
      $post->author_id = Auth::user()->user_id; //TODO Change this to the id of the regular_user
      if($group_id) $post->group_id = $group_id;
      if($event_id) $post->event_id = $event_id;
      $post->save();
      
      //Gets useful information about the post
      $new_post = Post::take(1)->where("post_id", '=', $post["post_id"])->get(); 
    
      return $new_post[0];
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


    public function like(Request $request, $id , $val)
    {
      $post = Post::find($id);
      //TODO: AUTHORIZE  
      $change = ['post_id' => $id ,'upvotes' => 0, 'downvotes' => 0];

      $like = DB::table('user_reaction')
                ->where('user_id', '=' , Auth::user()->user_id)
                ->where('post_id', '=' , $id)
                ->first();
      if(!$like){
        DB::table('user_reaction')
              ->insert(['user_id' => Auth::user()->user_id,
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
        if($like->like_or_dislike == $val){ //se o valor for igual então tirar o like
          DB::table('user_reaction')
                ->where('user_id', '=' , Auth::user()->user_id)
                ->where('post_id', '=' , $id)
                ->delete();
          if($val == 0){
            $post->decrement('downvotes');
            $change['downvotes'] = -1;
          }else{
            $post->decrement('upvotes');
            $change['upvotes'] = -1;
          }
        }else{ //mudar o like
          DB::table('user_reaction')
                ->where('user_id', '=' , Auth::user()->user_id)
                ->where('post_id', '=' , $id)
                ->update(['like_or_dislike' => $val]);
          if($val == 0){
            $post->increment('downvotes');
            $post->decrement('upvotes');
            $change['upvotes'] = -1;
            $change['downvotes'] = 1;
          }else{
            $post->increment('upvotes');
            $post->decrement('downvotes');
            $change['upvotes'] = 1;
            $change['downvotes'] = -1;
          }
        }
      }

      return $change;
    }

}