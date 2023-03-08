<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierService extends Model
{
    protected $fillable = ['supplier_id','service_id','coverage_area','service_location','status'];
    
    public function supplierdetails() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers', 'id','supplier_id');
    }
    public function serviceDetails() {
        return $this->hasOne('Modules\Services\Entities\Services', 'id','service_id');
    }
    public function serviceContent() {
        return $this->hasOne('App\SupplierServiceCustomization', 'supplier_service_id','id');
    }
    public function selectedEventtype() {
        return $this->hasMany('App\SupplierServiceEventType', 'supplier_service_id','id');
    }
    public function selectedAnswers() {
        return $this->hasMany('App\SupplierServiceStepAnswers', 'supplier_service_id','id');
    }
 }
