<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralBonus extends Model
{
    protected $table = 'referral_bonus';
    protected $fillable = ['referred_by','user_id','discount','status'];
  
}
