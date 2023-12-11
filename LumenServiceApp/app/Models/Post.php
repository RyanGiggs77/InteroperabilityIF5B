<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = array('title','content','status','image','video','user_id','categories_id','students_id');

    public $timestamps = true;

    public function user() 
    {
        return $this->belongsTo('App\Models\User');
    }
}

