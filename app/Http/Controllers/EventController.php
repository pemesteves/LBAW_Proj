<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
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
      $event = DB::transaction(function(){
        $event = new Event();
        $event->organization_id = Auth::user()->userable->regular_userable->organization_id;
        $event->name = Input::get('name');
        $event->location = Input::get('location');
        $event->information = Input::get('information');
        $event->date = Input::get('date');
        $event->save();

        DB::table('user_interested_in_event')->insert([
          'user_id' => Auth::user()->user_id,
          'event_id' => $event->event_id
        ]);

        return $event;
      });
    
      return redirect()->route('events.show', $event);
    }

    public function show_edit($id){
      if(!Auth::check()) return redirect('/login');

      $event = Event::find($id);
      if(!isset($event))
        throw new HttpException(404, "event");

      $this->authorize('edit', $event);

      if(!isset($event))
        throw new HttpException(404, "event");

      return view('pages.edit_event' , ['is_admin' => false , 'event' => $event ]);
    }

    /**
     * Edits the event
     */
    public function edit(Request $request, $id){
      $event = Event::find($id);

      if(!isset($event))
        throw new HttpException(404, "event");

      $this->authorize('edit', $event);

      $name = $request->input('name');
      $information = $request->input('information');
      $date = $request->input('date');
      $location = $request->input('location');

      $event->update(['name' => $name, 'information' => $information, 'date' => $date, 'location' => $location]);

      return EventController::show($event->event_id);
    }
}