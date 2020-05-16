<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'normal',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    /*public function groups(){
        return $this->userable->groups;
    }

    public function events(){
        return $this->userable->events;
        //return $this->belongsToMany('App\Event','user_interested_in_event','user_id','event_id');
    }*/

    public function chats(){
        return $this->belongsToMany('App\Chat', 'user_in_chat', 'user_id', 'chat_id');
    }


    public function postsLiked(){
        return $this->belongsToMany('App\Post' , 'user_reaction' , 'user_id' , 'post_id')->withPivot('like_or_dislike');
    }

    public function userable(){
        return $this->morphTo();
    }

    public function isAdmin(){
        return strcmp(get_class($this->userable) , "App\Admin") == 0;
    }

}
