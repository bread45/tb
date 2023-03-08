<?php

namespace Modules\Blogs\Entities;

use Illuminate\Database\Eloquent\Model;

class Blogs extends Model {

    protected $fillable = ['blog_category_id', 'title', 'sub_title', 'description', 'image', 'created_by', 'order_by', 'status', 'is_featured', 'created_time','slug','meta_title','meta_keywords','meta_description'];

    public function category() {
        return $this->belongsTo('Modules\Blogs\Entities\BlogCategories', 'blog_category_id', 'id');
    }

    public function tags() {
        return $this->hasMany('Modules\Blogs\Entities\BlogTags', 'blog_id', 'id');
    }

}
