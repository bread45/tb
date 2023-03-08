<?php

namespace Modules\Faq\Entities;

use Illuminate\Database\Eloquent\Model;

class FaqCategories extends Model {

    protected $table = 'faq_categories';
    protected $fillable = ['title', 'status', 'description'];

   

}
