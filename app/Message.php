<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;


  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'message';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'message_id';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'body',
  ];

  /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      
  ];


  /**
   * The user this post belongs to
   */
  public function user() {
    return $this->belongsTo('App\User' , 'sender_id');
  }

  /**
   * The group this post belongs to
   */
  public function chat() {
    return $this->belongsTo('App\Group', 'chat_id');
  }

}
