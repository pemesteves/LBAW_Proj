<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
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
        'name', 'location', 'date', 'information', 
    ];

    /**
     * The posta this event owns.
     */
    public function posts() {
      return $this->hasMany('App\Post');
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
}
