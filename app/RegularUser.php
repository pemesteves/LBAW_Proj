<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegularUser extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'regular_user';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'regular_user_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'personal_info', 'university',
    ];

    protected $with = ['user'];


    /**
     * The posts this user owns.
     */
    public function posts() {
        return $this->hasMany('App\Post', 'author_id', 'regular_user_id')->orderBy('date', 'desc');
      }

    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }

    public function regular_userable()
    {
        return $this->morphTo();
    }

    public function groups(){
        return $this->belongsToMany('App\Group','user_in_group','user_id','group_id');
    }

    public function chats(){
        return $this->belongsToMany('App\Chat', 'user_in_chat', 'user_id', 'chat_id');
    }

    public function notifications() {
        return $this->belongsToMany('App\Notification', 'notified_user', 'user_notified', 'notification_id' )->withPivot('seen');
    }

}
