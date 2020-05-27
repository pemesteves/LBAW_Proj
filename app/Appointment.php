<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Appointment extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appointment';




}


