<?php

namespace App\Http\Controllers;

use App\File;
use App\Image;
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
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller{

  /**
   * Show my profile
   */

  public function show_me(){
      if (!Auth::check()) return redirect('/login');

      $posts = Auth::user()->userable->posts;

      $groups = Auth::user()->userable->groups;

      $image = Auth::user()->userable->image();
      

      if(get_class(Auth::user()->userable->regular_userable) == "App\Organization") {
        $org_status = DB::table("organization_approval_request")
        ->where([['organization_approval_request.organization_id', '=', Auth::user()->userable->regular_userable->organization_id]]
        )->get();
        return view('pages.user' , ['is_admin' => false , 'user' => Auth::user()->userable, 'posts' => $posts , 'groups' => $groups, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'image' => $image, 'org_status' => $org_status]);
  
      }
      
      return view('pages.user' , ['is_admin' => false , 'user' => Auth::user()->userable, 'posts' => $posts , 'groups' => $groups, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'image' => $image]);
  }

  public function show_me_edit(){
    if (!Auth::check()) return redirect('/login');

    $image = Auth::user()->userable->image();
 
    return view('pages.user_me_edit' , ['is_admin' => false , 'notifications' => Auth::user()->userable->notifications, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'image' => $image]);


  }

  /**
   * Show someone profile
   */
  public function show($id){
    if (!Auth::check()) return redirect('/login');

    $user = RegularUser::find($id);

    if(!isset($user))
      throw new HttpException(404, "user");
    if($user->type == 'blocked' && !Auth::user()->isAdmin())
      throw new HttpException(404, "user");


    $posts = $user->posts;

    $groups = $user->groups;

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

    $image = $user->image();
    return view('pages.user' , ['is_admin' => false , 'user' => $user, 'friendship_status' => $friendship_status, 'posts' => $posts, 'groups' => $groups,  'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'image' => $image ]);
  }

  /**
   * Edits the user profile
   */
  public function edit(Request $request){
    if(!Auth::check()) return redirect('/login');

    $request->validate([
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'name' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú]$/i|max:255',
      'university' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú]$/i|max:255',
      'personal_info' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
    ]);

    DB::transaction(function() use ($request){
      $user = Auth::user();

      $name = Input::get('name');
      $university = Input::get('university');
      $personal_info = Input::get('personal_info');

      $user->update(['name' => $name]);
      $user->userable->update(['personal_info' => $personal_info, 'university' => $university]);

      if(request()->image !== null){
        $request->validate([
          'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
        ]);
        $imageName = time().'.'.request()->image->getClientOriginalExtension();

        request()->image->move(public_path('images/users'), $imageName);

        try{
          $image = Image::where('regular_user_id', '=', $user->userable->regular_user_id)->get()[0];
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
        $file->file_path = '/images/users/' . $imageName;
        $file->save();

        $image = new Image();
        $image->file_id = $file->file_id;
        $image->regular_user_id = $user->userable->regular_user_id;
        $image->save();
      }
    });

    Session::flash("success_message", "Profile updated successfully.");
    
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