<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Groups extends Authenticatable {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
     public function user()
    {
        return $this->hasOne('Modules\Users\Entities\FrontUsers', 'id', 'user_id');
    }

}