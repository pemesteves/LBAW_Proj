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
        return DB::table('appointment')->whereNull('teacher_id')->orWhere('teacher_id',$this->teacher_id)->rightJoin('timeUnit','time_id','=','timeUnit_id')->orderBy('timeUnit_id')->get();
    }

}
