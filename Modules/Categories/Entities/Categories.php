<?php

namespace Modules\Categories\Entities;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model {

    protected $fillable = ['name', 'slug', 'status', 'image', 'is_popular', 'description'];

    public function services() {
        return $this->hasMany('Modules\Services\Entities\Services','category_id','id');
    }
}
