<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Report;
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

      $posts = $event->posts;

      $going = $event->going();

      $can_create_events  = Auth::user()->userable->regular_userable_type === 'App\Organization';

      $owner = false;
      if($can_create_events && Auth::user()->userable->regular_userable->organization_id === $event->organization_id){
          $owner = true;
      }

      return view('pages.event' , ['is_admin' => false , 'event' => $event, 'posts' => $posts, 'going' => $going, 'can_create_events' => $can_create_events, 'is_owner' => $owner ]);
    }

    public function showCreateForm(){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Event');

      return view('pages.create_event', ['is_admin' => false, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
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

      return view('pages.edit_event' , ['is_admin' => false , 'event' => $event, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization' ]);
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

    /**
     * Report a event.
     *
     * @return Post The event reported.
     */
    public function report(Request $request, $id)
    { 
      $title = $request->input('title');
      $description = $request->input('description');
      $reporter_id = Auth::user()->userable->regular_user_id;

      $report = new Report();
      $report->title = $title;
      $report->reason = $description;
      $report->reporter_id = $reporter_id;
      $report->reported_event_id = $id;

      $report->save();
      return $report;
    }


}