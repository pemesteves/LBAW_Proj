<?php

namespace App;

class Admin extends User
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'admin_id';

    /*public function user(){
        return $this->hasOne(User::class);
    }*/

    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }

}
