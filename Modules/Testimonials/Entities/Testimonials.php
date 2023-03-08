<?php

namespace Modules\Testimonials\Entities;

use Illuminate\Database\Eloquent\Model;

class Testimonials extends Model {

    protected $fillable = ['user_id', 'title', 'user_name', 'position', 'user_image', 'description', 'rating', 'order_by', 'status','show_slider'];

    public function users() {
        return $this->belongsTo('Modules\Users\Entities\FrontUsers', 'user_id', 'id');	
    }

}
