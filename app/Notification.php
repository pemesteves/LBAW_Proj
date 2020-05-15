<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;


  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'notification';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'notification_id';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'origin_user_id',
      'description',
      'link',
      'date'
  ];

  /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      
  ];

  protected $with = [

  ];

  /**
 * The user this notification belongs to
 */
  public function triggerUser() {
    return $this->belongsTo('App\RegularUser' , 'origin_user_id');
  }


  /**
   * The user this notification belongs to
   */
  public function users() {
    return $this->belongsToMany('App\RegularUser' , 'notified_user', 'notification_id', 'user_notified')->withPivot('seen');
  }


}
