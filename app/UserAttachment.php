<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class UserAttachment extends Model
{
    //
    // table name
    protected $table = "user_attachments";
    // primary key
    protected $primaryKey = 'id';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = array('is_visible', 'attached_by', 'file_original_name', 'attachable_type', 'attachable_id', 'file_type', 'link_url');

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
    //protected $casts = array
    
    public function attachable(){
        return $this->morphTo();
    }
    
    //one to many (inverse)
    public function attachedBy(){
        $user = new User();
        $user->mail = $this->attached_by;
        $user->getUser();
        $user->thumbnailphoto = null;
        return $user;
    }
}
