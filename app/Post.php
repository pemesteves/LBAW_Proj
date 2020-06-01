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
      'title', 'body', 'type'
  ];

  /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      
  ];

  protected $with = ['regularUser'];

  /**
   * The user this post belongs to
   */
  public function regularUser() {
    return $this->belongsTo('App\RegularUser' , 'author_id');
  }

  public function comments(){
    return $this->hasMany('App\Comment' , 'post_id')
                ->where('type','<>','blocked')
                ->orderBy('comment.date', 'desc');
  }

  /**
   * The group this post belongs to
   */
  public function group() {
    return $this->belongsTo('App\Group', 'group_id');
  }


  public function userLikes(){
    return $this->belongsToMany('App\RegularUser' , 'user_reaction' , 'post_id' , 'user_id')->withPivot('like_or_dislike');
  }

  public function hasContext(){
    if($this->event_id || $this->group_id)
      return true;
    return false;
  }

  public function getLink(){
    if($this->event_id){
      return "./events/" . $this->event_id;
    }
    if($this->group_id){
      return "./groups/" . $this->group_id;
    }
    return "#";
  }

  public function getContext(){
    if($this->event_id){
      return Event::find($this->event_id)->name;
    }
    if($this->group_id){
      return Group::find($this->group_id)->name;
    }
    return "";
  }

  public function file(){
    $file = File::take(1)
                 ->where('file.post_id', '=', $this->post_id)
                 ->get();
    if(count($file) === 0)
      return null;

    return $file[0];
  }

  public function image(){
    $image = Image::take(1)
                  ->where('image.post_id', '=', $this->post_id)
                  ->join('file', 'file.file_id', '=', 'image.file_id')
                  ->get();

    if(count($image) === 0)
      return null;
    
    return $image[0];  
  } 
}
