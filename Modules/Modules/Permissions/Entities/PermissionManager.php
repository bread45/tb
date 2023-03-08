<?php

namespace Modules\Permissions\Entities;

use Illuminate\Database\Eloquent\Model;

class PermissionManager extends Model {

    protected $table = 'permission_manager';
    protected $fillable = ['role_id', 'route_id', 'status'];

    public function routes() {
        return $this->belongsTo('Modules\Permissions\Entities\RoutesManager', 'route_id', 'id');
    }

    public function roles() {
        return $this->belongsTo('App\Roles', 'role_id', 'id');
    }

}
