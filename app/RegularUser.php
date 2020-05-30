<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'personal_info', 'university','type'
    ];

    protected $with = ['user'];


    /**
     * The posts this user owns.
     */
    public function posts() {
        return $this->hasMany('App\Post', 'author_id', 'regular_user_id')->where("type",'normal')->orderBy('date', 'desc');
    }

    public function archived_posts() {
    return $this->hasMany('App\Post', 'author_id', 'regular_user_id')->where("type",'archived')->orderBy('date', 'desc');
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
        return $this->belongsToMany('App\Group','user_in_group','user_id','group_id')->where("type",'normal');
    }

    public function events(){
        return $this->belongsToMany('App\Event','user_interested_in_event','user_id','event_id')->where("type",'normal');
    }

    public function chats(){
        return $this->belongsToMany('App\Chat', 'user_in_chat', 'user_id', 'chat_id');
    }

    public function notifications() {
        return $this->belongsToMany('App\Notification', 'notified_user', 'user_notified', 'notification_id' )->orderBy('date','DESC')->withPivot('seen');
    }

    public function numberOfNotifications() {
        return $this->belongsToMany('App\Notification', 'notified_user', 'user_notified', 'notification_id' )
                ->withPivot('seen')->where('seen',false)->count();
    }

    /**
     * Get user image
     */
    public function image() {
        $image = DB::table('image')
                   ->where('image.regular_user_id', '=', $this->regular_user_id)
                   ->join('file', 'file.file_id', '=', 'image.file_id')->get();
        
        if($image->count() === 0)
            return null;
            
        return $image[0];
    }
    
    public function friends(){
        return $this->belongsToMany('App\RegularUser', 'friend', 'friend_id1', 'friend_id2')->wherePivot('type', 'accepted');
    }

    /**
     * $user : App\RegularUser object
     */
    public function friendsInCommun($user){
        return $this->friends->intersect($user->friends);
    }
    
    public function friendsInCommunWithMe(){
        return $this->friendsInCommun(Auth::user()->userable);
    }

}
