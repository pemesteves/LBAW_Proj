<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Report;
use App\Event;
use App\File;
use App\Image;
use ErrorException;
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

      $interested = DB::table("user_interested_in_event")
                    ->where([['user_id',Auth::user()->userable->regular_user_id],['event_id',$id]])->get();

      $can_create_events  = Auth::user()->userable->regular_userable_type === 'App\Organization';

      $owner = false;
      if($can_create_events && Auth::user()->userable->regular_userable->organization_id === $event->organization_id){
          $owner = true;
      }

      $image = $event->image();

      return view('pages.event' , ['css' => ['navbar.css','event.css','posts.css','post_form.css','feed.css'],
      'js' => ['event.js','post.js','infinite_scroll.js','general.js', 'uploadImages.js'] ,'interested'=>$interested , 'event' => $event, 'posts' => $posts, 'going' => $going, 'can_create_events' => $can_create_events, 'is_owner' => $owner, 'image' => $image]);
    }

    public function show_statistics($id){
      if (!Auth::check()) return redirect('/login');

      $event = Event::find($id);
      if(!isset($event))
        throw new HttpException(404, "event");

      if($event->type == "blocked" && !Auth::user()->isAdmin())
        throw new HttpException(404, "event");

      // Only a admin can see the statistics, like in the edit option
      $this->authorize('edit', $event);

      $going = $event->going();
      
      $can_create_events  = Auth::user()->userable->regular_userable_type === 'App\Organization';

      $image = $event->image();

      $users_posts = array();
      $postsPerDay = array();

      $posts = $event->posts;
      
      foreach($posts as $post){
        if(isset($users_posts[$post->regularUser->regular_user_id])){
          $users_posts[$post->regularUser->regular_user_id]++;
        }else{
          $users_posts[$post->regularUser->regular_user_id] = 1;
        }

        $postDate = date('d-m-Y', strtotime($post->date));
        if(isset($postsPerDay[$postDate])){
          $postsPerDay[$postDate]++;
        }else{
          $postsPerDay[$postDate] = 1;
        }
      }

      $numberPosts = [
        0 => 0,
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0
      ];

      foreach($users_posts as $numPosts){
          if($numPosts === 0){
            $numberPosts[0]++;
          }else if ($numPosts <= 5){
            $numberPosts[1]++;
          }else if ($numPosts <= 10){
            $numberPosts[2]++;
          }else if ($numPosts <= 15){
            $numberPosts[3]++;
          }else {
            $numberPosts[4]++;
          }
      }
      
      $postsPerUser = array(
        array("label"=> "0 posts", "y"=> $numberPosts[0]),
        array("label"=> "1-5 posts", "y"=> $numberPosts[1]),
        array("label"=> "6-10 posts", "y"=> $numberPosts[2]),
        array("label"=> "11-15 posts", "y"=> $numberPosts[3]),
        array("label"=> "+15 posts", "y"=> $numberPosts[4])
      );

      $postsPerDayOfYear = array();
      foreach(array_keys($postsPerDay) as $post_date){
        array_push($postsPerDayOfYear, array("x" => strtotime($post_date)* 1000, "y" => $postsPerDay[$post_date]));
      }

      try{
        $firstDay = reset($postsPerDayOfYear)["x"];
      }catch(ErrorException $e){
        $firstDay = null;
      }

      try{
        $lastDay = end($postsPerDayOfYear)["x"];
      }catch(ErrorException $e){
        $lastDay = null;
      }

      $interested = DB::table('user_interested_in_event')
        ->where('event_id', '=', $event->event_id)
        ->orderBy('date', 'asc')
        ->get();
        
      $usersPerDay = array();
      foreach($interested as $interestedUser){
        $interestDate = date('d-m-Y', strtotime($interestedUser->date));
        if(isset($usersPerDay[$interestDate])){
          $usersPerDay[$interestDate]++;
        }else{
          $usersPerDay[$interestDate] = 1;
        }
      }

      $lastNumUsers = null;
      $newUsersPerDay = array();
      foreach(array_keys($usersPerDay) as $date){
        if($lastNumUsers !== null){
          $usersPerDay[$date] += $lastNumUsers;
          array_push($newUsersPerDay, array("x" => strtotime($date)* 1000, "y" => $usersPerDay[$date]));
        }else{
          array_push($newUsersPerDay, array("x" => strtotime($date)* 1000, "y" => $usersPerDay[$date]));
        }
        $lastNumUsers = $usersPerDay[$date];
      }

      reset($usersPerDay);
      return view('pages.event_statistics' , ['css' => ['navbar.css','event.css','posts.css','post_form.css','feed.css','statistics.css'],
      'js' => ['event.js','post.js','infinite_scroll.js','general.js', 'uploadImages.js'] ,
      'event' => $event, 'going' => $going, 'can_create_events' => $can_create_events, 'image' => $image,
      'posts_per_user'=>$postsPerUser, 'postsPerDay' => $postsPerDay, 'postsPerDayOfYear' => $postsPerDayOfYear,
      'firstDay' => $firstDay, 'lastDay' => $lastDay, 'usersPerDay' => $newUsersPerDay,
      'firstUserDay' => key($usersPerDay), 'lastUserDay' => array_key_last($usersPerDay)]);
    }

    public function showCreateForm(){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Event');

      return view('pages.create_event', ['css' => ['navbar.css','event.css','posts.css','post_form.css','feed.css','create.css' ], 'js' => ['uploadImages.js','general.js'] , 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
    }

    public function create(Request $request){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Event');

      $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'name' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+(?:[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú])?$/i|max:255',
        'information' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i|max:255",
        'location' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+(?:[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú])?$/i|max:255',
        'date' => 'required|date|after:today',
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
        'user_id' => Auth::user()->userable->regular_user_id,
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

      return view('pages.edit_event' , ['css' => ['navbar.css','event.css','posts.css','post_form.css','feed.css'], 'js' => ['uploadImages.js','general.js'], 'event' => $event, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization' ]);
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
        'name' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+(?:[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú])?$/i|max:255',
        'information' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i|max:255",
        'location' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+(?:[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú])?$/i|max:255',
        'date' => 'required|date|after:today',
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
        'title' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+(?:[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú])?$/i|max:255',
        'description' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i|max:255",
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
      
      return $image;
    }

    function desinterest($id){
      DB::table('user_interested_in_event')->where([['user_id',Auth::user()->userable->regular_user_id],['event_id',$id]])->delete();
      return ;
    }
    function interest($id){
      DB::table('user_interested_in_event')->insert(['user_id' => Auth::user()->userable->regular_user_id,'event_id' => $id]);
      return ;
    }

    function getPosts($event_id,$last_id){

      $event = Event::find($event_id);

      $myEventsPosts = Post::join('user_interested_in_event' , "post.event_id" , "user_interested_in_event.event_id")
            ->where([['user_interested_in_event.user_id', Auth::user()->userable->regular_user_id],['post.type','normal']])
            ->where([['post_id','<',$last_id],['post.event_id',$event_id]])
            ->select("post.*")
            ->orderBy('date','desc')->limit(3)->get();

      return view('requests.posts',['posts' => $myEventsPosts]);
    }

}