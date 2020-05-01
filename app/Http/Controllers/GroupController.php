<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Group;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GroupController extends Controller{

    public function show($id){
      if (!Auth::check()) return redirect('/login');

      $group = Group::find($id);
      if(!isset($group))
        throw new HttpException(404, "group");

      $posts = Post::join('group','group.group_id','=', 'post.group_id')
                     ->where('group.group_id', '=',  $id)
                     ->orderBy('date','desc')
                     ->get();

      $members = $group->members();

      return view('pages.group' , ['is_admin' => false , 'group' => $group, 'posts' => $posts, 'members' => $members ]);
    }


}