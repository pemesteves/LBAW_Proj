<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


session_start();

class ResetController extends Controller
{


    public function showReset(){
        return view("auth.reset",['css' => ['authentication.css'],'js' => ['resetPass.js']]);
    }

    public function addEmail(Request $request){
        $email = $request->only('email');
        $user = User::where("email", "like",$email)->first();
        if(!$user)
            return ['success' => 0 , 'error' => 'Email not found.'];

        $code = openssl_random_pseudo_bytes(3);

        //Convert the binary data into hexadecimal representation.
        $code = bin2hex($code);

        $hash = hash("sha256",$code);

        $data = array(
            'code'=> $code,
        );

        Mail::send('emails.simple',$data, function($message) use($email){
        $message->from("uconnectlbaw@gmail.com","UConnect");
        $message->to($email)
                ->subject("UConnet: Reset Password code");
        
        });
        $_SESSION['code'] = $hash;
        $_SESSION['email'] = $email;
        $_SESSION['max_tries'] = 3;


        return ['success' => 1];
    }

    public function checkCode(Request $request){
        $email = $request->only('email');
        $code = $request->only('code');

        $user = User::where("email", "like",$email)->first();
        if(!$user)
            return ['success' => 0, 'error' => 'Email not found'];

        $hash = hash("sha256",implode($code));

        if(strcmp($hash,$_SESSION['code']) != 0){
            $_SESSION['max_tries'] = $_SESSION['max_tries'] -1;
            if($_SESSION['max_tries'] == 0){
                unset($_SESSION['code']);
                unset($_SESSION['email']);
                unset($_SESSION['max_tries']);
                return ['success' => -1, 'error' => 'Exceded max tries.'];
            }
            return ['success' => 0, 'error' => 'Invalid code.'];
        }

        return ['success' => 1];
    }

    public function reset(Request $request){

        $email = $request->only('email');
        $code = $request->only('code');
        $pass = $request->only('pass');

        $user = User::where("email", "like",$email)->first();

        if(!$user)
            return ['success' => 0, 'error' => 'Email not found'];
        if(strcmp(implode($email),implode($_SESSION['email'])) != 0)
            abort(401);
        $hash = hash("sha256",implode($code));
        if(strcmp($hash,$_SESSION['code']) != 0)
            return ['success' => 0, 'error' => 'Invalid code'];


        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code'  => 'required|string',
            'pass' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails())
            abort(400);
        
        $user->password = Hash::make(implode($pass));
        $user->save();


        return redirect('/login');

    }
    

}
