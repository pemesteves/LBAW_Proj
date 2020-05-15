<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;



use App\Post;
use App\RegularUser;
use App\User;
use Exception;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProfileController extends Controller{

  /**
   * Show my profile
   */

  public function show_me(){
      if (!Auth::check()) return redirect('/login');

      $posts = Post::join('regular_user','post.author_id','=', 'user_id')
                      ->where('user_id', '=',  Auth::user()->userable->regular_user_id)
                      ->leftJoin("report" , "post.post_id","report.reported_post_id")
                      ->whereNull('report.approval')
                      ->orderBy('post.date','desc')
                      ->select("post.*")
                      ->get();

      $groups = Auth::user()->userable->groups;

      $friends = RegularUser::join('friend', 'friend_id2', '=', 'regular_user_id')
                      ->where([
                        ['friend.friend_id1', '=', Auth::user()->userable_id],
                        ['friend.type','accepted']
                      ])
                      ->get();

      return view('pages.user' , ['is_admin' => false , 'user' => Auth::user()->userable, 'posts' => $posts ,'notifications' => Auth::user()->userable->notifications, 'groups' => $groups, 'friends' => $friends, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
  }

  public function show_me_edit(){
    if (!Auth::check()) return redirect('/login');

    $posts = Post::join('regular_user','post.author_id','=', 'user_id')
                    ->where('user_id', '=',  Auth::user()->userable->regular_user_id)
                    ->leftJoin("report" , "post.post_id","report.reported_post_id")
                    ->whereNull('report.approval')
                    ->orderBy('post.date','desc')
                    ->select("post.*")
                    ->get();

    return view('pages.user_me_edit' , ['is_admin' => false , 'posts' => $posts, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization' ]);

  }

  /**
   * Show someone profile
   */
  public function show($id){
    if (!Auth::check()) return redirect('/login');

    $user = RegularUser::find($id);

    if(!isset($user))
      throw new HttpException(404, "user");


    $posts = Post::join('regular_user','post.author_id','=', 'user_id')
                    ->where('user_id', '=',  $id)
                    ->leftJoin("report" , "post.post_id","report.reported_post_id")
                    ->whereNull('report.approval')
                    ->orderBy('post.date','desc')
                    ->select("post.*")
                    ->get();

    $groups = $user->user->userable->groups;

    $friends = RegularUser::join('friend', 'friend_id2', '=', 'regular_user_id')
                    ->where([
                      ['friend.friend_id1', '=', $user->regular_user_id],
                      ['friend.type','accepted']
                    ])
                    ->get();
    $friendship_status = DB::table("friend")
                        ->where([
                          ['friend.friend_id1', '=', $user->regular_user_id],
                          ['friend.friend_id2', '=',Auth::user()->userable_id],
                        ])->get();
    if(count($friendship_status) == 0){
      $friendship_status = DB::table("friend")
                        ->where([
                          ['friend.friend_id2', '=', $user->regular_user_id],
                          ['friend.friend_id1', '=',Auth::user()->userable_id],
                        ])->get();
    }

    return view('pages.user' , ['is_admin' => false , 'user' => $user,'notifications' => Auth::user()->userable->notifications, 'friendship_status' => $friendship_status, 'posts' => $posts, 'groups' => $groups, 'friends' => $friends, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization' ]);

  }

  /**
   * Edits the user profile
   */
  public function edit(Request $request){
    if(!Auth::check()) return redirect('/login');

    DB::transaction(function(){
      $user = Auth::user();

      $name = Input::get('name');
      $university = Input::get('university');
      $personal_info = Input::get('personal_info');

      $user->update(['name' => $name]);
      $user->userable->update(['personal_info' => $personal_info, 'university' => $university]);
    });
    
    return ProfileController::show_me();
  }



  public function email(){
    if (!Auth::check()) return redirect('/login');

    //Generate a random string.
    $token = openssl_random_pseudo_bytes(16);
    
    //Convert the binary data into hexadecimal representation.
    $token = bin2hex($token);

    $data = array(
          'url'=> $_SERVER['HTTP_HOST'] . "/resetPass/" . strval($token),
    );

    Mail::send('emails.simple',$data, function($message){
      $message->from("uconnectlbaw@gmail.com","Hello test");
      $message->to('uconnectlbaw@gmail.com')
              ->subject('This is a test email');
      
    });

    return "Your email has been sended sucessfully";

  }

}