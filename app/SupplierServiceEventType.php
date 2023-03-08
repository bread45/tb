<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierServiceEventType extends Model
{
    protected $table = 'supplier_services_event_type';
    protected $fillable = ['supplier_service_id','event_type_id'];
       
}
