<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscriptionplan';
    protected $fillable = [
        'subcription_plan',
        'price',
        'status',
        'created_at',
        'updated_at'
    ];
}
