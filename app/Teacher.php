<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Teacher extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'teacher';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'teacher_id';

    /*public function regular_user(){
        return $this->hasOne(RegularUser::class);
    }*/

    public function regular_user()
    {
        return $this->morphOne('App\User', 'regular_userable');
    }

}
