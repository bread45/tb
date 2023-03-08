<?php

namespace Modules\Services\Entities;

use Illuminate\Database\Eloquent\Model;

class Services extends Model {

    protected $fillable = [
        "category_id",
        "name",
        "slug",
        "sort_description",
        "description",
        "about",
        "image",
        "is_featured",
        "status",
        "order_by",
        "tags",
        "spot_supplier_id",
    ];

    public function category() {
        return $this->hasOne('Modules\Categories\Entities\Categories', 'id', 'category_id');
    }
    public function suppliers() {
        return $this->hasMany('App\SupplierService','service_id','id');
    }
    public function spotlight() {
        return $this->belongsTo('Modules\Users\Entities\FrontUsers','spot_supplier_id','id');
    }
    public function dynamicsteps() {
        return $this->hasMany('App\RequestStepsRelation','service_id','id');
    }
}
