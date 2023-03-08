<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteRequestsStepValue extends Model
{
    protected $table = 'quote_requests_step_value';
    protected $fillable = ['quote_request_id','request_step_id','answer'];
       
}
