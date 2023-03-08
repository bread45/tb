<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeAccounts extends Model
{
    protected $table = 'stripe_accounts';
    protected $fillable = ['user_id','access_token','refresh_token','token_type','stripe_publishable_key','stripe_user_id'];
       
}
