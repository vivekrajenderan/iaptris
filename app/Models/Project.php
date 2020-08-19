<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'project';

    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'fk_category_id','fk_season_id','fk_designertype_id','stylefor','brandname','brandimage',
        'deliverytime','designbudget','createdBy','status'];

    /*
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = ['password', 'remember_token'];
}
