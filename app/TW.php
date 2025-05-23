<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class TW extends Model
{
    //
    // table name
    protected $table = "t_w_s";
    // primary key
    protected $primaryKey = 'id';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = array('is_visible', 'created_user', 'company_name', 'department_name', 'title', 'description', 'meeting_category_id', 'status_id', 'start_date', 'due_date', 'piority', 'is_done', 'done_user', 'done_date', 'resource_dir', 'is_cloned', 'is_cloned_child', 'cloned_parent_id', 'is_archived', 'is_reviewable');

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
    
    /*public static function boot()
    {
        parent::boot();
        TW::observe(new \App\Observers\TWObserver);
    }*/
    
    //one to many
    public function twUsers(){
        return $this->hasMany('App\TWUser', 't_w_id', 'id');
    }
    
    //one to many
    public function twInfos(){
        return $this->hasMany('App\TWInfo', 't_w_id', 'id');
    }
    
    //one to many (inverse)
    public function status(){
        return $this->belongsTo('App\Status', 'status_id', 'id');
    }
    
    //one to many (inverse)
    public function meetingCategory(){
        return $this->belongsTo('App\MeetingCategory', 'meeting_category_id', 'id');
    }
    
    //one to many (inverse)
    public function createdUser(){
        $user = new User();
        $user->mail = $this->created_user;
        $user->getUser();
        $user->thumbnailphoto = null;
        return $user;
    }
    
    //one to many (inverse)
    public function doneUser(){
        $user = new User();
        $user->mail = $this->done_user;
        $user->getUser();
        $user->thumbnailphoto = null;
        return $user;
    }
    
    //many to many
    public function eventRecurringPatterns(){
        return $this->morphMany('App\EventRecurringPattern', 'recurrable');
    }
    
    //one to many
    public function twChildren(){
        return $this->hasMany('App\TW', 'cloned_parent_id', 'id');
    }
    
    //one to many (inverse)
    public function twParent(){
        return $this->belongsTo('App\TW', 'cloned_parent_id', 'id');
    }
    
}
