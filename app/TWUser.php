<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class TWUser extends Model
{
    //
    // table name
    protected $table = "t_w_users";
    // primary key
    protected $primaryKey = 'id';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = array('is_visible', 't_w_id', 'own_user', 'company_name', 'department_name', 'is_done', 'is_cloned', 'is_archived', 'is_reviewable', 'is_remindable');

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
    
    //one to many (inverse)
    public function ownUser(){
        $user = new User();
        $user->mail = $this->own_user;
        $user->getUser();
        $user->thumbnailphoto = null;
        return $user;
    }
}
