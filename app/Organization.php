<?php

namespace App;

class Organization extends RegularUser
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
        'approval' => 'FALSE',
    ];

    public function regular_user(){
        return $this->hasOne(RegularUser::class);
    }

}
