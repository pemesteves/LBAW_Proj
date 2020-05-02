<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Event;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EventController extends Controller{

    public function show($id){
      if (!Auth::check()) return redirect('/login');

      $event = Event::find($id);
      if(!isset($event))
        throw new HttpException(404, "event");

      $posts = Post::join('event','post.event_id','=', 'event.event_id')
                     ->where('event.event_id', '=',  $id)
                     ->orderBy('event.date','desc')
                     ->get();

      $going = $event->going();

      return view('pages.event' , ['is_admin' => false , 'event' => $event, 'posts' => $posts, 'going' => $going ]);
    }

    public function showCreateForm(){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Event');

      return view('pages.create_event', ['is_admin' => false]);
    }

    public function create(Request $request){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Event');
      $event = new Event();
      $event->organization_id = Auth::user()->userable->regular_userable->organization_id;
      $event->name = $request->input('name');
      $event->location = $request->input('location');
      $event->information = $request->input('information');
      $event->date = $request->input('date');
      $event->save();
    
      return redirect()->route('events.show', $event);
    }

    /**
     * Edits the event
     */
    /*public function edit(Request $request){
      $user = Auth::user();

      /*$input = $request->only('name');

      $user->update(['name' => $input['name']]);*/

      /*return ProfileController::show_me();
    }*/
}