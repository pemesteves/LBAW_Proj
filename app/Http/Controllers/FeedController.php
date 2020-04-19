<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;

class FeedController extends Controller{

    public function show(){
        if (!Auth::check()) return redirect('/login');

        $posts = Post::join('user','post.author_id','=','user_id')->orderBy('date','desc')->get();

        return view('pages.feed' , ['is_admin' => false , 'posts' => $posts ]);

    }
}