<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $table = 'availabilities';
    protected $fillable = [
        'user_id',
        'available',
        'working_days',
    ];
}
