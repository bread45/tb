<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceCategory extends Model
{
    protected $table = 'resource_category';
    
    protected $fillable = ['trainer_id','name','created_at'];
    
}
