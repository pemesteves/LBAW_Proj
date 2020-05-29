<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RegularUser;

class Event extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'event_id';

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
        'name', 'location', 'date', 'information', 'updated_at', 'created_at',
    ];

    /**
     * The posts this event owns.
     */
    public function posts() {
        if(Auth::user()->isAdmin())
            return $this->hasMany('App\Post', 'event_id', 'event_id')
                        ->orderBy('date', 'desc');
        return $this->hasMany('App\Post', 'event_id', 'event_id')->where("type",'normal')->orderBy('date', 'desc');
    }

    /**
     * Count the number of users that go to the event
     */
    public function going() {
        $result = $this->join('user_interested_in_event', 'event.event_id', '=', 'user_interested_in_event.event_id')
                       ->where('event.event_id', '=', $this->event_id)
                       ->select('*')
                       ->count();

        return $result;
    }

    public function interested(){
        return $this->belongsToMany('App\RegularUser','user_interested_in_event','event_id','user_id');
    }

    /**
     * Get event image
     */
    public function image() {
        $image = DB::table('image')
                   ->where('image.event_id', '=', $this->event_id)
                   ->join('file', 'file.file_id', '=', 'image.file_id')->get();
        
        if($image->count() === 0)
            return null;
            
        return $image[0];
    }
}
