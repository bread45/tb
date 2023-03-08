<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierServiceCustomization extends Model
{
    protected $table = 'supplier_service_customization';
    protected $fillable = ['supplier_service_id','description','status'];
       
}
