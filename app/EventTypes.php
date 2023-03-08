<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventTypes extends Model
{
    protected $table = 'event_types';
    protected $fillable = ['title','description','status'];
       
}
