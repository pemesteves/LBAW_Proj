<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Message;
use App\RegularUser;
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

      return view('pages.chat' , ['in_chat' => $chat->in_chat, 'chat' => $chat, 'messages' => $messages, 'members' => $members, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'notifications' => $notifications]);
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

    public function getFriends(Request $request,$chat_id){
      $str = strtolower($request->input('string'));
      $suggestions = RegularUser::join('user','regular_user.user_id','user.user_id')->join('friend','friend_id1','regular_user_id')
              ->where([['friend_id2',Auth::user()->userable->regular_user_id],['friend.type','accepted']])
              ->whereRaw('lower(name) LIKE \'%'.$str.'%\'')
              ->leftjoin('image','image.regular_user_id', '=', 'regular_user.regular_user_id')
              ->leftjoin('file', 'file.file_id', '=', 'image.file_id')
              ->whereNOTIn('regular_user.regular_user_id',function($query) use ($chat_id){
                $query->select('user_id')->from('user_in_chat')
                ->where([['chat_id',$chat_id]]);
              })
              ->get();
      return ['str' => $str , 'new_members' => $suggestions];
  }

    public function addToChat($chat_id,$user_id){
      $chat = Chat::find($chat_id);
      $this->authorize('add', $chat);
      DB::table('user_in_chat')->insert(['user_id' => $user_id,'chat_id' => $chat_id]);
      return $user_id;
    }


    public function create(Request $request) {

      $chat = new Chat();
      $chat->chat_name = $request->input('name');
      $chat->save();

      DB::table('user_in_chat')->insert(['user_id' => Auth::user()->userable->regular_user_id,'chat_id' => $chat->chat_id]); 

      $new_chat = Chat::take(1)->where("chat_id", '=', $chat["chat_id"])->get(); 
      
      return $new_chat[0];
    }

}