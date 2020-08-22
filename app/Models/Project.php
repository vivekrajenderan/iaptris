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
    protected $fillable = ['stylenumber', 'fk_category_id', 'fk_season_id', 'fk_designertype_id', 'stylefor', 'brandname', 'brandimage',
        'deliverytime', 'designbudget', 'createdBy', 'status','projectamount','projectstatus'];

    /*
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    //protected $hidden = ['password', 'remember_token'];


    public function category() {
        return $this->belongsTo('App\Models\Category', 'fk_category_id', 'id');
    }

    public function season() {
        return $this->belongsTo('App\Models\Season', 'fk_category_id', 'id');
    }

    public function designertype() {
        return $this->belongsTo('App\Models\Designertype', 'fk_category_id', 'id');
    }

}
