<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersPlan extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'plan_id',
        'payment_id',
        'signature',
        'token',
        'billing_date',
        'is_active'
    ];

    public function subscriptionPlan() {
        return $this->hasOne('App\SubscriptionPlan', 'id','subscription_plan_id');
    }
}
