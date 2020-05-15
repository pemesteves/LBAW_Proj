<?php

namespace App\Http\Controllers;

use App\Http\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;



use App\Post;
use App\RegularUser;
use App\User;
use App\Notification;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegularUserController extends Controller{

    use NotificationTrait;

    function sendRequest($id){
        DB::table('friend')->insert(['friend_id1' => Auth::user()->userable->regular_user_id, 'friend_id2' => $id]);

        $notification =  new Notification;
        $notification->origin_user_id = Auth::user()->userable->regular_user_id;
        $notification->notification_user_id = Auth::user()->userable->regular_user_id;
        $notification->description = "sent you a friend request.";
        $notification->link = "#";
        $notification->save();

        $this->sendNotification($notification,$id);

        return ['result' => true , 'user_id' => $id];
    }

    function cancelRequest($id){
        DB::table('friend')->where(['friend_id1' => Auth::user()->userable->regular_user_id, 'friend_id2' => $id])->delete();
        return ['result' => true , 'user_id' => $id];
    }

    function acceptRequest($id){
        $friend = DB::table('friend')
            ->where(['friend_id1' => Auth::user()->userable->regular_user_id, 'friend_id2' => $id])
            ->update(['type' => 'accepted']);
        if($friend == false){
            $friend = DB::table('friend')
            ->where(['friend_id2' => Auth::user()->userable->regular_user_id, 'friend_id1' => $id])
            ->update(['type' => 'accepted']);
        }
        return ['result' => true , 'user_id' => $id];
    }

    function declineRequest($id){
        $friend = DB::table('friend')
            ->where(['friend_id1' => Auth::user()->userable->regular_user_id, 'friend_id2' => $id])
            ->update(['type' => 'refused']);
        if($friend == false){
            $friend = DB::table('friend')
            ->where(['friend_id2' => Auth::user()->userable->regular_user_id, 'friend_id1' => $id])
            ->update(['type' => 'refused']);
        }
        return ['result' => true , 'user_id' => $id];
    }


    function removeFriend($id){
        $friend = DB::table('friend')
            ->where(['friend_id1' => Auth::user()->userable->regular_user_id, 'friend_id2' => $id])
            ->delete();
        $friend = DB::table('friend')
            ->where(['friend_id2' => Auth::user()->userable->regular_user_id, 'friend_id1' => $id])
            ->delete();
        return ['result' => true , 'user_id' => $id];
    }

}