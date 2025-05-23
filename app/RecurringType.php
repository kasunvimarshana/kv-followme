<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecurringType extends Model
{
    //
    // table name
    protected $table = "recurring_types";
    // primary key
    protected $primaryKey = 'id';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = array('is_visible','is_active','name');

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
    public function recurringPatterns(){
        return $this->hasMany('App\RecurringPattern', 'recurring_type_id', 'id');
    }
    
    //one to many
    public function eventRecurringPatterns(){
        return $this->hasMany('App\EventRecurringPattern', 'recurring_type_id', 'id');
    }
    
}
