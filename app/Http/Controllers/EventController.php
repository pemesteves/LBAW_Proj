<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Report;
use App\Event;
use App\File;
use App\Image;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Session;

class EventController extends Controller{

    public function show($id){
      if (!Auth::check()) return redirect('/login');

      $event = Event::find($id);
      if(!isset($event))
        throw new HttpException(404, "event");

      if($event->type == "blocked" && !Auth::user()->isAdmin())
        throw new HttpException(404, "event");

      $posts = $event->posts;

      $going = $event->going();

      $can_create_events  = Auth::user()->userable->regular_userable_type === 'App\Organization';

      $owner = false;
      if($can_create_events && Auth::user()->userable->regular_userable->organization_id === $event->organization_id){
          $owner = true;
      }

      $image = $event->image();

      return view('pages.event' , ['is_admin' => false , 'event' => $event, 'posts' => $posts, 'going' => $going, 'can_create_events' => $can_create_events, 'is_owner' => $owner, 'image' => $image]);
    }

    public function showCreateForm(){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Event');

      return view('pages.create_event', ['is_admin' => false, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
    }

    public function create(Request $request){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Event');

      $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'name' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú]$/i|max:255',
        'information' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
        'location' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú]$/i|max:255',
        'date' => 'required|date',
      ]);

      $event = DB::transaction(function() use ($request) {
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
      
      $imageName = time().'.'.request()->image->getClientOriginalExtension();

      request()->image->move(public_path('images/events'), $imageName);

      $file = new File();
      $file->file_path = '/images/events/' . $imageName;
      $file->save();

      $image = new Image();
      $image->file_id = $file->file_id;
      $image->event_id = $event->event_id;
      $image->save();

      return $event;
      });
    
      return redirect()->route('events.show', $event);
    }

    public function show_edit($id){
      if(!Auth::check()) return redirect('/login');

      $event = Event::find($id);
      if(!isset($event))
        throw new HttpException(404, "event");

      if($event->type == "blocked" && !Auth::user()->isAdmin())
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

      $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'name' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú]$/i|max:255',
        'information' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
        'location' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú]$/i|max:255',
        'date' => 'required|date',
      ]);

      $name = $request->input('name');
      $information = $request->input('information');
      $date = $request->input('date');
      $location = $request->input('location');

      if(request()->image !== null)
        $this->upload_image($request, $id);
      
      $event->update(['name' => $name, 'information' => $information, 'date' => $date, 'location' => $location]);

      Session::flash("success_message", "Event updated successfully.");

      return EventController::show($event->event_id);
    }

    /**
     * Report a event.
     *
     * @return Post The event reported.
     */
    public function report(Request $request, $id)
    { 

      $request->validate([
        'title' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú]$/i|max:255',
        'description' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
      ]);

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


    public function upload_image(Request $request, $event_id){
      $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);
      $imageName = time().'.'.request()->image->getClientOriginalExtension();

      request()->image->move(public_path('images/events'), $imageName);

      try{
        $image = Image::where('event_id', '=', $event_id)->get()[0];
      }catch(Exception $e){
        $image = null;
      }

      if(isset($image) && $image !== null){ // Delete image and file
        $file_id = $image->file_id;
        $image->delete();
        
        $file = File::find($file_id);
        $file->delete();
      }
      
      $file = new File();
      $file->file_path = '/images/events/' . $imageName;
      $file->save();

      $image = new Image();
      $image->file_id = $file->file_id;
      $image->event_id = $event_id;
      $image->save();
      error_log($image);
      return $image;
    }

}