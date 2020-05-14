<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Comment;

class Report extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'report';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'report_id';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'approval',
  ];

  /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      
  ];

  function referenceTo(){
    if($this->reported_user_id)
        return "User";
    if($this->reported_event_id)
        return "Event";
    if($this->reported_post_id)
        return "Post";
    if($this->reported_comment_id)
        return "Comment";
    if($this->reported_group_id)
        return "Group";

    return "Undefined";
  }


  function link(){
    if($this->reported_user_id)
        return "users/" . $this->reported_user_id;
    if($this->reported_event_id)
        return "events/" . $this->reported_event_id;
    if($this->reported_post_id)
        return "posts/" . $this->reported_post_id;
    if($this->reported_comment_id){
      $comment = Comment::where('comment_id',$this->reported_comment_id)->first();
      return "posts/" . $comment->post_id . "#comment_" . $this->reported_comment_id;
    }
    if($this->reported_group_id)
      return "groups/" . $this->reported_group_id;

    return "#";
  }

}
