<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class GroupsUsers extends Authenticatable {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'group_users';
    protected $fillable = ['user_id','group_id'];
    
    public function group()
    {
        return $this->hasOne('App\Groups', 'id', 'group_id');
    }
    public function user()
    {
        return $this->hasOne('Modules\Users\Entities\FrontUsers', 'id', 'user_id');
    }

}