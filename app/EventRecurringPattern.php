<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventRecurringPattern extends Model
{
    //
    // table name
    protected $table = "event_recurring_patterns";
    // primary key
    protected $primaryKey = 'id';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = array('is_visible','recurrable_type','recurrable_id','is_recurring','recurring_type_id','minute','hour','day','day_of_month','month','day_of_week','year','has_max_number_of_occures','max_number_of_occures','has_seperation_count','seperation_count','last_event_at','next_event_at','number_of_occures');

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
    
    //one to many (inverse)
    public function recurringType(){
        return $this->belongsTo('App\RecurringType', 'recurring_type_id', 'id');
    }
    
    public function recurrable(){
        return $this->morphTo();
    }
}
