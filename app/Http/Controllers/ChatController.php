<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Message;
use App\Chat;

class ChatController extends Controller{

    public function show($id){
      if (!Auth::check()) return redirect('/login');

      $chat = Chat::find($id);

      $messages = Message::join('chat','chat.chat_id','=', 'message.chat_id')
                     ->where('chat.chat_id', '=',  $id)
                     ->orderBy('date','desc')
                     ->get();

      $members = $chat->members();

      return view('pages.chat' , ['is_admin' => false , 'chat' => $chat, 'messages' => $messages, 'members' => $members ]);
    }


}