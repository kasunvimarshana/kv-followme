<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEscalateOff extends Model
{
    //
    // table name
    protected $table = "user_escalate_offs";
    // primary key
    protected $primaryKey = 'id';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = array('id', 'is_visible', 'is_escalate', 'user_pk', 'email_escalate');

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
}
