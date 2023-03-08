<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'resource';
    
    protected $fillable = ['trainer_id','name','title','category','description','format','format_name', 'tags', 'status','created_at'];
    
}
