<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;


  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'comment';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'comment_id';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'body',
      'date',
      'upvotes',
      'downvotes',
  ];

  /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      
  ];

  protected $with = ['user'];


  /**
   * The post this comment belongs to
   */
  public function post() {
    return $this->belongsTo('App\Post' , 'post_id');
  }

  /**
   * The user this comment belongs to
   */
  public function user() {
    return $this->belongsTo('App\RegularUser' , 'user_id');
  }


}
