<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBucket extends Model
{
    protected $table = 'users_bucket';
    protected $fillable = ['supplier_id','media_url',
    'media_description',
    'type',
];

}
