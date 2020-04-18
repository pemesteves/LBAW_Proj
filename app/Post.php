<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  /**
   * The user this card belongs to
   */
  public function user() {
    return $this->belongsTo('App\User');
  }

}
