<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierServiceStepAnswers extends Model
{
    protected $table = 'supplier_service_step_answers';
    protected $fillable = ['supplier_service_id','request_step_id','answers'];
}
