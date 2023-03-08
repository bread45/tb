<?php

namespace Modules\Testimonials\Entities;

use Illuminate\Database\Eloquent\Model;

class Testimonials extends Model {

    protected $fillable = ['user_id','sub_title', 'title', 'description', 'order_by', 'status','show_slider'];

    public function users() {
        return $this->belongsTo('Modules\Users\Entities\FrontUsers', 'user_id', 'id');
    }

}
