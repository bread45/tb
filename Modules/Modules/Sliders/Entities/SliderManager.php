<?php

namespace Modules\Sliders\Entities;

use Illuminate\Database\Eloquent\Model;

class SliderManager extends Model {

    protected $table = 'sliders';
    protected $fillable = [
        'name',
        'status',
        'description',
    ];

}
