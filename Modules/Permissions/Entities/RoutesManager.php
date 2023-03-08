<?php

namespace Modules\Permissions\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Permissions\Entities\Modules;

class RoutesManager extends Model {

    protected $table = 'route_manager';
    protected $fillable = ['module_id', 'route_name', 'label', 'status'];

    public function modules() {
        return $this->belongsTo('Modules\Permissions\Entities\Modules', 'module_id', 'id');
    }

}
