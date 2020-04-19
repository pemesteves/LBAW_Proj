<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\User;

class ProfileController extends Controller{

    public function show_me(){
        if (!Auth::check()) return redirect('/login');

        $posts = Post::join('user','post.author_id','=', 'user_id')
                       ->where('user_id', '=',  Auth::user()->user_id)
                       ->orderBy('date','desc')
                       ->get();

        return view('pages.user_me' , ['is_admin' => false , 'posts' => $posts ]);

    }

    public function show_me_edit(){
      if (!Auth::check()) return redirect('/login');

      $posts = Post::join('user','post.author_id','=', 'user_id')
                     ->where('user_id', '=',  Auth::user()->user_id)
                     ->orderBy('date','desc')
                     ->get();

      return view('pages.user_me_edit' , ['is_admin' => false , 'posts' => $posts ]);

    }

    public function show($id){
      if (!Auth::check()) return redirect('/login');

      $user = User::find($id);



      $posts = Post::join('user','post.author_id','=', 'user_id')
                     ->where('user_id', '=',  $id)
                     ->orderBy('date','desc')
                     ->get();

      return view('pages.user' , ['is_admin' => false , 'user' => $user, 'posts' => $posts ]);

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


}