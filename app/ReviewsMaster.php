<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewsMaster extends Model
{
    protected $table = 'reviews_master';
    protected $fillable = [
        'quote_id',
        'supplier_id',
        'user_id',
        'title',
        'comment',
        'response',
        'is_verified',
        'review_date'
    ];

    public function ratingfetch() {
        return $this->hasMany('App\ReviewRatings', 'review_id','id');
    }
    public function reviewsBy() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers', 'id','user_id');
    }
    public function responseBy() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers', 'id','supplier_id');
    }
    public function reviewsBucket() {
        return $this->hasMany('App\ReviewsMasterBucket', 'review_id','id');
    }
    
}
