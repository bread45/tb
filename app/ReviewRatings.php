<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewRatings extends Model
{
    protected $table = 'review_ratings';
    protected $fillable = ['review_id','rating_type_id','rating'];

    public function rattingTypes() {
        return $this->hasOne('App\RattingTypes', 'id','rating_type_id');
    }   
}
