<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Message;
use App\Events\NewMessage;

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

      $request->validate([
          'body' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
      ]);

      $message->body = $request->input('body');
      $message->chat_id = $chat_id;
      $message->sender_id = Auth::user()->userable->regular_user_id;
      $message->save();

      $user_name = Auth::user()->name;
      $id = Auth::user()->userable->regular_user_id;
      $image = Auth::user()->userable->image();

      if ($image == null) {
        $image = "";
      }

      //Gets useful information about the message
      $new_message = Message::take(1)->where("message_id", '=', $message["message_id"])->get(); 

      broadcast(new NewMessage($message, $image, $user_name, $id))->toOthers();

      return $new_message[0];
    }
}