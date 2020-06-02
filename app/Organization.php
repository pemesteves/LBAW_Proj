<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organization';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'organization_id';


     /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        
    ];

    /*public function regular_user(){
        return $this->hasOne(RegularUser::class);
    }*/

    public function regular_user()
    {
        return $this->morphOne('App\User', 'regular_userable');
    }

    public function applied_users(){
        return $this->belongsToMany('App\RegularUser','user_in_org','organization_id','user_id')->where('user_in_org.type', '=', 'pending');
      }

    public function members() {
        return $this->belongsToMany('App\RegularUser','user_in_org','organization_id','user_id')->where('user_in_org.type', '=', 'accepted');
    }



}
