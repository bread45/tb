<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewsMasterBucket extends Model
{
    protected $table = 'reviews_master_buckets';
    protected $fillable = ['review_id', 'attachment'];
}
