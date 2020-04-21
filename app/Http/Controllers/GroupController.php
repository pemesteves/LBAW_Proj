<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Group;

class GroupController extends Controller{

    public function show($id){
      error_log("ola");
      if (!Auth::check()) return redirect('/login');

      $group = Group::find($id);

      $posts = Post::join('group','post.group_id','=', 'post.group_id')
                     ->where('group.group_id', '=',  $id)
                     ->orderBy('date','desc')
                     ->get();

      return view('pages.group' , ['is_admin' => false , 'group' => $group, 'posts' => $posts ]);

    }
}