<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Student extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'student';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'student_id';

    /*public function regular_user(){
        return $this->hasOne(RegularUser::class);
    }*/

    public function regular_user()
    {
        return $this->morphOne('App\User', 'regular_userable');
    }

}
