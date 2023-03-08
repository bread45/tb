<?php

namespace Modules\Users\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Notifications\FrontUserPasswordReset;
use Laravel\Passport\HasApiTokens;


class FrontUsers extends Authenticatable implements MustVerifyEmail
{

    protected $guard = 'front_auth';

    use Notifiable,
        HasApiTokens;

    protected $fillable = [
        "business_name",
        "business_number",
        "service_location",
        "coverage_area",
        "first_name",
        "last_name",
        "phone_number",
        "day1",
        "day2",
        "day3",
        "day4",
        "day5",
        "day6",
        "day7",
        "address_1",
        "address_2",
        "city",
        "state",
        "country",
        "zip_code",
        "website",
        "email",
        "email_verified_at",
        "user_role",
        "status",
        "is_feature",
        "is_sponsored",
        "is_verfied",
        "password",
        "remember_token",
        "spot_description",
        "affiliate_id",
        "referred_by",
        "referral_wallet",
        "confirmed"
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new FrontUserPasswordReset($token));
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser', 'user_id', 'id');
    }

    public function reviewmaster()
    {
        return $this->hasMany('App\ReviewsMaster', 'supplier_id', 'id');
    }
    public function supplierservice()
    {
        return $this->hasMany('App\SupplierService', 'supplier_id', 'id');
    }
    public function additionaldetails()
    {
        return $this->belongsTo('App\UserBusinessDetails', 'id', 'supplier_id');
    }
    public function userBuckets()
    {
        return $this->hasMany('App\UserBucket', 'supplier_id', 'id');
    }
    public function services() {
        return $this->hasMany('App\TrainerServices','trainer_id','id');
    }
    public function onlyservices() {
        return $this->hasOne('App\TrainerServices','trainer_id','id');
    }

    public function featuredservice() {
        return $this->hasOne('App\TrainerServices','trainer_id','id')->where(['is_featured' => "yes", "status" => 1]);
    }

    public function orders() {
        return $this->hasMany('Modules\Orders\Entities\Orders','trainer_id','id')->where('type','=', 'order');
    }

    public function allOrders() {
        return $this->hasMany('Modules\Orders\Entities\Orders','trainer_id','id');
    }

    public function ratings() {
        return $this->hasMany('App\Ratings','trainer_id','id');
    }
    public function Providerlocations() {
        return $this->hasMany('App\ProviderLocations','user_id','id');
    }
    
}
