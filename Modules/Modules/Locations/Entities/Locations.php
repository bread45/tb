<?php

namespace Modules\Locations\Entities;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model {

    protected $fillable = [
        'name', 'latitude', 'longitude','status'
    ];

}
