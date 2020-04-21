<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    // Don't add create and update timestamps in database.
    //public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'group_id';

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
        'name', 'information', 
    ];

    /**
     * The post this group owns.
     */
     public function posts() {
      return $this->hasMany('App\Post');
    }
}
