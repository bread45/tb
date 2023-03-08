<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RattingTypes extends Model
{
    protected $table = 'rating_types';
    protected $fillable = ['title','status'];
       
}
