<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Appointment;

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

    public function appointments(){

        $myAppoint = DB::table('appointment')->where('teacher_id',$this->teacher_id);

        $all = DB::table('timeUnit')->leftjoinSub($myAppoint , 'app' , function ($join) {
                $join->on('timeUnit_id', '=', 'app.time_id');
            })->get();

        return $all;
    }

}
