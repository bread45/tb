<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestStepsRelation extends Model
{
    protected $table = 'request_steps_relation';
    protected $fillable = ['request_step_id','service_id'];
    public function stepdata() {
        return $this->belongsTo('App\RequestSteps','request_step_id','id');
    }
}
