<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;

class ProfileController extends Controller{

    public function show_me(){
        if (!Auth::check()) return redirect('/login');

        $posts = Post::join('user','post.author_id','=', 'user_id')
                       ->where('user_id', '=',  Auth::user()->user_id)
                       ->orderBy('date','desc')
                       ->get();

        return view('pages.profile' , ['is_admin' => false , 'posts' => $posts ]);

    }

    public function show_me_edit(){
      if (!Auth::check()) return redirect('/login');

      $posts = Post::join('user','post.author_id','=', 'user_id')
                     ->where('user_id', '=',  Auth::user()->user_id)
                     ->orderBy('date','desc')
                     ->get();

      return view('pages.profile_edit' , ['is_admin' => false , 'posts' => $posts ]);

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
      $post->author_id = Auth::user()->user_id;
      $post->save();

      return $post;
    }


    /**
     * Edits the user profile
     */
    public function edit(Request $request){
      $user = Auth::user();

      $input = $request->only('name');

      error_log("Name: " . $input['name']);

      $user->update(['name' => $input['name']]);

      return ProfileController::show_me();
    }

}