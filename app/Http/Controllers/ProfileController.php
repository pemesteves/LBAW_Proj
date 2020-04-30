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




class ProfileController extends Controller{

    public function show_me(){
        if (!Auth::check()) return redirect('/login');

        $posts = Post::join('user','post.author_id','=', 'user_id')
                       ->where('user_id', '=',  Auth::user()->userable->regular_user_id)
                       ->orderBy('date','desc')
                       ->get();

        $groups = Auth::user()->groups;

        $friends = User::join('friend', 'friend_id2', '=', 'userable_id')
                       ->where('friend.friend_id1', '=', Auth::user()->userable_id)
                       ->get();

        return view('pages.user_me' , ['is_admin' => false , 'posts' => $posts , 'groups' => $groups, 'friends' => $friends]);
    }

    public function show_me_edit(){
      if (!Auth::check()) return redirect('/login');

      $posts = Post::join('user','post.author_id','=', 'user_id')
                     ->where('user_id', '=',  Auth::user()->userable->regular_user_id)
                     ->orderBy('date','desc')
                     ->get();

      return view('pages.user_me_edit' , ['is_admin' => false , 'posts' => $posts ]);

    }

    public function show($id){
      if (!Auth::check()) return redirect('/login');

      $user = RegularUser::find($id);



      $posts = Post::join('user','post.author_id','=', 'user_id')
                     ->where('user_id', '=',  $id)
                     ->orderBy('date','desc')
                     ->get();

      $groups = $user->user->groups;

      $friends = User::join('friend', 'friend_id2', '=', 'userable_id')
                      ->where('friend.friend_id1', '=', $user->regular_user_id)
                      ->get();

      return view('pages.user' , ['is_admin' => false , 'user' => $user, 'posts' => $posts, 'groups' => $groups, 'friends' => $friends ]);

    }

    /**
     * Edits the user profile
     */
    public function edit(Request $request){
      $user = Auth::user();

      $input = $request->only('name');

      $user->update(['name' => $input['name']]);

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