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
     * Creates a new post.
     *
     * @return Post The post created.
     */
    public function create(Request $request)
    {
      $post = new Post();

      $this->authorize('create', $post);

      $post->title = $request->input('title');
      $post->body = $request->input('body');
      $post->author_id = Auth::user()->user_id; //TODO Change this to the id of the regular_user
      $post->save();
      
      //Gets useful information about the post
      $new_post = Post::take(1)->where("post_id", '=', $post["post_id"])->get(); 
    
      return $new_post[0];
    }
}