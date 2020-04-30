<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Message;
use App\Event\NewMessage;

class MessageController extends Controller{

    /**
     * Creates a new message.
     *
     * @return Message The message created.
     */
    public function create(Request $request, $chat_id)
    {
      $message = new Message();

      $this->authorize('create', $message);

      $message->body = $request->input('body');
      $message->chat_id = $chat_id;
      $message->sender_id = Auth::user()->user_id;//Auth::user()->user_id; //Change this to the id of the regular_user
      $message->save();

      //Gets useful information about the message
      $new_message = Message::take(1)->where("message_id", '=', $message["message_id"])->get(); 

      broadcast(new NewMessage($message))->to_others();

      return $new_message[0];
    }
}