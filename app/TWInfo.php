<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class TWInfo extends Model
{
    //
    // table name
    protected $table = "t_w_infos";
    // primary key
    protected $primaryKey = 'id';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = array('is_visible', 't_w_id', 'description', 'created_user');

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
    public function tw(){
        return $this->belongsTo('App\TW', 't_w_id', 'id');
    }
    
    //many to many
    public function userAttachments(){
        return $this->morphMany('App\UserAttachment', 'attachable');
    }
    
    //one to many (inverse)
    public function createdUser(){
        $user = new User();
        $user->mail = $this->created_user;
        $user->getUser();
        $user->thumbnailphoto = null;
        return $user;
    }
}
