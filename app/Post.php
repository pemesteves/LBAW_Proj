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
  protected $table = 'post';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'post_id';

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

  protected $with = ['user'];

  /**
   * The user this post belongs to
   */
  public function user() {
    return $this->belongsTo('App\User' , 'author_id');
  }

  public function comments(){
    return $this->hasMany('App\Comment' , 'post_id');
  }

  /**
   * The group this post belongs to
   */
  public function group() {
    return $this->belongsTo('App\Group', 'group_id');
  }


}
