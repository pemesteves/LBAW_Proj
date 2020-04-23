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
        'personal_info', 
    ];

    protected $with = ['user'];


    /**
     * The posts this user owns.
     */
    public function posts() {
        return $this->hasMany('App\Post');
      }

    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }

    public function regular_userable()
    {
        return $this->morphTo();
    }

}
