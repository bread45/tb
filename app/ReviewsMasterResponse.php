<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewsMasterResponse extends Model
{
    protected $table = 'reviews_master_buckets';
    protected $fillable = ['review_id', 'user_id', 'comment'];
}
