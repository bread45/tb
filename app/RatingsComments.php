<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RatingsComments extends Model
{
    protected $table = 'rating_comments';
    protected $fillable = ['rating_id','comment']; 
}
