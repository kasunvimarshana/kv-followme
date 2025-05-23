<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingCategory extends Model
{
    //
    // table name
    protected $table = "meeting_categories";
    // primary key
    protected $primaryKey = 'id';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = array('is_visible', 'name');

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    //protected $hidden = array();

    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
    //protected $casts = array();
    
    //one to many
    public function tws(){
        return $this->hasMany('App\TW', 'meeting_category_id', 'id');
    }
}
