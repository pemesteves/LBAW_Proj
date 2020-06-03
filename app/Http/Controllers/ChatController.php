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
                     ->orderBy('date','desc')
                     ->limit(20)
                     ->get()->reverse();

        

      $members = $chat->members();
      DB::table("user_in_chat")->where([['chat_id',$chat->chat_id],['user_id',Auth::user()->userable->regular_user_id]])->update(['not_seen' => 0]);
     

      return view('pages.chat' , ['css' => ['navbar.css','chat.css'],'js' => ['chat.js','general.js'],'in_chat' => $chat->in_chat, 'chat' => $chat, 'messages' => $messages, 'members' => $members, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
    }

    public function get_chat(){
      if (!Auth::check()) return redirect('/login');
      return view ('pages.chat', ['css' => ['navbar.css', 'chat.css'], 'js' =>['chat.js','general.js'], 'chat' => null, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
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
      $user = RegularUser::find($user_id);
      return ['chat_id' => $chat_id,'user' => $user,'image' => $user->image()];
    }

    public function clear(Request $request, $id) {

      $chat = Chat::find($id);
      DB::table("user_in_chat")->where([['chat_id',$chat->chat_id],['user_id',Auth::user()->userable->regular_user_id]])->update(['not_seen' => 0]);
      
      return $chat;
    }

    public function create(Request $request) {

      $chat = new Chat();
      $chat->chat_name = $request->input('name');
      $chat->save();

      DB::table('user_in_chat')->insert(['user_id' => Auth::user()->userable->regular_user_id,'chat_id' => $chat->chat_id]); 

      $new_chat = Chat::take(1)->where("chat_id", '=', $chat["chat_id"])->get(); 
      
      return $new_chat[0];
    }


    public function deleteUser(Request $request, $chat_id) {

      $userInChat = DB::table('user_in_chat')->where(['user_id'=>$request->input('user_id'), 'chat_id' => $chat_id ])->first();
       DB::table('user_in_chat')->where(['user_id'=>$request->input('user_id'), 'chat_id' => $chat_id ])->delete();

      return json_encode($userInChat);
      
    }

    public function getMessages($chat_id,$id){

      $chat = Chat::find($chat_id);

      if(!isset($chat))
        throw new HttpException(404, "chat");

      $this->authorize('show', $chat);

      $messages = Message::join('chat','chat.chat_id','=', 'message.chat_id')
                     ->where([['chat.chat_id', '=',  $chat_id],['message.message_id','<',$id]])
                     ->orderBy('date','desc')
                     ->limit(10)
                     ->get()->reverse();

      return view('requests.messages',['messages' => $messages]);

    }

}