<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //
    public function user(){
        return $this->belongsTo('App\User','id_user');
    }

    public function post(){
        return $this->belongsTo('App\Post','id_post');
    }
}
