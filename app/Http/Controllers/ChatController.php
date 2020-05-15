<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Message;
use App\Chat;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ChatController extends Controller{

    public function show($id){
      if (!Auth::check()) return redirect('/login');

      $chat = Chat::find($id);

      if(!isset($chat))
        throw new HttpException(404, "chat");

      $this->authorize('show', $chat);

      $messages = Message::join('chat','chat.chat_id','=', 'message.chat_id')
                     ->where('chat.chat_id', '=',  $id)
                     ->orderBy('date','asc')
                     ->get();

      $members = $chat->members();

      $notifications = Auth::user()->userable->notifications;

      return view('pages.chat' , ['is_admin' => false , 'chat' => $chat, 'messages' => $messages, 'members' => $members, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'notifications' => $notifications]);
    }

    public function get_chat(){
      if (!Auth::check()) return redirect('/login');

      try{
        $chat_id = DB::table('user_in_chat')
                      ->where('user_id', '=', Auth::user()->userable->regular_user_id)
                      ->join('message', 'user_in_chat.chat_id', '=', 'message.chat_id')
                      ->orderBy('date', 'asc')
                      ->select('message.chat_id as chat_id')
                      ->limit(1)
                      ->get()[0]->chat_id;        
        return redirect()->route('chats.show', $chat_id);
      }catch(Exception $exception){
        return redirect()->route('feed');
      }
    }

}