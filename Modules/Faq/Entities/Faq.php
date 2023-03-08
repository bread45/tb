<?php

namespace Modules\Faq\Entities;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model {

    protected $table = 'faq';
    protected $fillable = ['categories_id', 'title', 'description', 'image', 'order_by', 'status'];

    public function faqcategory() {
        return $this->belongsTo('Modules\Faq\Entities\FaqCategories', 'categories_id', 'id');
    }

}
