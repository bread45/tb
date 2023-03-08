<?php

namespace Modules\Contactus\Entities;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model {

    protected $fillable = [
        'name', 'phone_number', 'message','email','type'
    ];

}
