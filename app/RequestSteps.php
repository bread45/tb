<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestSteps extends Model
{
    protected $table = 'request_steps';
    protected $fillable = ['services','title','question','status','answers','answer_choice'];
       
}
