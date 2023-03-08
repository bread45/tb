<?php

namespace Modules\Sliders\Entities;

use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model {

    protected $table = 'slider_images';
    protected $fillable = [
        'name',
        'slider_id',
        'image',
        'status',
        'shortdescription',
        'description',
    ];

    public function slider() {
        return $this->belongsTo('Modules\Sliders\Entities\SliderManager', 'slider_id', 'id');
    }

}
