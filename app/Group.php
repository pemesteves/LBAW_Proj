<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'group_id';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'normal',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'information', 
    ];

    /**
     * The posts this group owns.
     */
    public function posts() {
      return $this->hasMany('App\Post', 'group_id', 'group_id');
    }

    /**
     * Count the number of members of the group
     */
    public function members() {
        $result = $this->join('user_in_group', 'group.group_id', '=', 'user_in_group.group_id')
                       ->where('group.group_id', '=', $this->group_id)
                       ->select('*')
                       ->count();

        return $result;
    }
}
