<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
    protected $table = 'ratings';
    protected $fillable = ['order_id','title','rating','description','trainer_id','user_id'];
       
    public function Orders() {
        return $this->hasOne('Modules\Orders\Entities\Orders', 'id','order_id');
    }

    public function user() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers','id','user_id');
    }
    public function trainer() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers','id','trainer_id');
    }
}
