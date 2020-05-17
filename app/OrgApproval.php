<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Organization;

class OrgApproval extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'organization_approval_request';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'request_id';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'approval',
  ];

  /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      
  ];

  function referenceTo(){
    return $this->organization_id;
  }


  function link(){
    return "users/" . Organization::find($this->organization_id)->regular_user_id;
  }

   /**
   * The organization this request belongs to
   */
  public function organization() {
    return $this->belongsTo('App\Organization' , 'organization_id');
  }

}
