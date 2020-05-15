<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;
use App\Group;

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
 * The user this notification was triggered by
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

  function link(){
    if($this->notification_user_id)
        return "users/" . $this->origin_user_id;
    if($this->notification_event_id)
        return "events/" . $this->notification_event_id;
    if($this->notification_post_id)
        return "posts/" . $this->notification_post_id;
    if($this->notification_comment_id){
      $comment = Comment::where('comment_id',$this->notification_comment_id)->first();
      return "posts/" . $comment->post_id . "#comment_" . $this->notification_comment_id;
    }
    if($this->notification_group_id)
      return "groups/" . $this->notification_group_id;

    return "#";
  }

  function getDescription(){
    $description = "";

    //return $this->triggerUser->user->name . ": " . $this->description;

    if($this->notification_user_id)
      $description = $this->triggerUser->user->name . $this->description;
    if($this->notification_event_id)
      $description = $this->triggerUser->user->name . " has posted in " . Event::find($this->notification_event_id)->name;
    if($this->notification_post_id)
      $description = $this->description;
    if($this->notificationcomment_id)
        $description = $this->triggerUser->user->name . " has commented in your post";
    if($this->notification_group_id)
      $description = $this->triggerUser->user->name . " has posted in " . Group::find($this->notification_event_id)->name;
    else
      $description = $this->triggerUser->user->name . ": " . $this->description;
    return $description;
  }


}
