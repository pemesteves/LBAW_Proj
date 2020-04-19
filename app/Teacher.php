<?php

namespace App;

class Teacher extends RegularUser
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

    public function regular_user(){
        return $this->hasOne(RegularUser::class);
    }

}
