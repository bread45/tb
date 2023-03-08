<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
    protected $table = 'subscribe';
    protected $fillable = [
        'email',
        'created_at',
    ];
}
