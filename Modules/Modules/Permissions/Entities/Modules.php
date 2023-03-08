<?php

namespace Modules\Permissions\Entities;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model {

    protected $fillable = ['name', 'keyword', 'status'];

    public function routes_list() {
        return $this->hasMany('Modules\Permissions\Entities\RoutesManager', 'module_id', 'id');
    }

}
