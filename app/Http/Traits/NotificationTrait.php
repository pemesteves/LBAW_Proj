<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;

use App\Notification;

trait NotificationTrait {

    /**
     * notification: notification object
     * regular_user_id : the regular user id to send to
     */

    public function sendNotification($notification, $regular_user_id){

        DB::table("notified_user")->insert(
            ["notification_id" => $notification->notification_id , "user_notified" => $regular_user_id]
        );

        return;
    }

    public function sendNotifications($notification, $regular_user_ids){

        $arr = [];

        foreach($regular_user_ids as $id){
            array_push($arr,["notification_id" => $notification->notification_id , "user_notified" => $id]);
        }

        DB::table("notified_user")->insert(
            $arr
        );

        return;
    }

}