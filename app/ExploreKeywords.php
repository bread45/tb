<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExploreKeywords extends Model
{
    protected $table = 'keyword_explore';
    protected $fillable = [
        'keywords'
    ];
}
