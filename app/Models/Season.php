<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'season';

    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'status'];

    /*
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = ['password', 'remember_token'];
}
