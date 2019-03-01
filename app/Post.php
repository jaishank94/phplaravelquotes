<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    public function user(){
        return $this->belongsTo('App\User','id_user');
    }

    public function likes(){
        return $this->hasMany('App\Like','id_like');
    }

}
