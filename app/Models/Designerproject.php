<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designerproject extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'designerproject';

    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_project_id', 'fk_user_id','status'];

    /*
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    //protected $hidden = ['password', 'remember_token'];

}
