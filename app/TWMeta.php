<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TWMeta extends Model
{
    //
    // table name
    protected $table = "t_w_metas";
    // primary key
    protected $primaryKey = 'id';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = array('is_visible', 'meta_key', 'meta_value');

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
