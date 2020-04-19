<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;

class FeedController extends Controller{

    public function show(){
        if (!Auth::check()) return redirect('/login');

        $posts = Post::orderBy('date','desc')->get();

        return view('pages.feed' , ['is_admin' => false , 'posts' => $posts]);

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
      $post->author_id = 3;
      $post->save();

      return $post;
    }


}