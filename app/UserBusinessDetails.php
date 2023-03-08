<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBusinessDetails extends Model
{
    protected $table = 'users_business_details';
    protected $fillable = ['supplier_id','subtitle',
    'description',
    'dob',
    'addressline1',
    'addressline2',
    'postcode',
    'city',
    'country',
    'profile_pic',
    'facebook',
    'twitter',
    'instagram',
    'business_type',
    'vat_registration_no',
    'insurance_provider',
    'public_liability_cover',
    'insurance_expire_date',
    'company_number',
    'payment_accept',
    'typical_client'
];

}
