<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;


  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'chat';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'chat_id';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
  ];

  /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
  ];

  public function messages(){
    return $this->hasMany('App\Message');
  }

  public function members() {

        $result = $this->join('user_in_chat', 'chat.chat_id', '=', 'user_in_chat.chat_id')
                   ->join('regular_user', 'user_in_chat.user_id', '=', 'regular_user.regular_user_id')
                   ->where('chat.chat_id', '=', $this->chat_id)
                   ->select('*')
                   ->count();
 
    return $result;
  }

}
