<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Event;

class EventController extends Controller{

    public function show($id){
      if (!Auth::check()) return redirect('/login');

      $event = Event::find($id);

      $posts = Post::join('event','post.event_id','=', 'event.event_id')
                     ->where('event.event_id', '=',  $id)
                     ->orderBy('event.date','desc')
                     ->get();

      $going = $event->going();

      return view('pages.event' , ['is_admin' => false , 'event' => $event, 'posts' => $posts, 'going' => $going ]);
    }
}