<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        if(Auth::user()->isAdmin())
            return $this->hasMany('App\Post', 'group_id', 'group_id')
                        ->orderBy('date', 'desc')->limit(5);
        return $this->hasMany('App\Post', 'group_id', 'group_id')->where("type",'<>','blocked')
                    ->orderBy('date', 'desc')->limit(5);
    }
    /**
     * Get members of the group
     */
    public function members() {
        $result = $this->join('user_in_group', 'group.group_id', '=', 'user_in_group.group_id')
                       ->join('regular_user', 'user_in_group.user_id', '=', 'regular_user.regular_user_id')
                       ->select('user_id', 'regular_user_id', 'group_id')
                       ->join('user', 'user.user_id', '=', 'regular_user.user_id') 
                       ->where('group.group_id', '=', $this->group_id)
                       ->select('*')
                       ->get();

        return $result;
    }

    /**
     * Count the number of members of the group
     */
    public function member_count() {
        $result = $this->join('user_in_group', 'group.group_id', '=', 'user_in_group.group_id')
                       ->where('group.group_id', '=', $this->group_id)
                       ->select('*')
                       ->count();

        return $result;
    }

    /**
     * Get group image
     */
    public function image() {
        $image = DB::table('image')
                   ->where('image.group_id', '=', $this->group_id)
                   ->join('file', 'file.file_id', '=', 'image.file_id')->get();
        
        if($image->count() === 0)
            return null;
            
        return $image[0];
    }

}
