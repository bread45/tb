<?php

namespace Modules\Orders\Entities;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = [];
    
    public function Users() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers','id','user_id');
    }
    public function trainer() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers','id','trainer_id');
    }

    public function Ratting() {
        return $this->hasOne('App\Ratings','order_id','id');
    }
    public function service() {
        return $this->hasOne('App\TrainerServices','id','service_id');
    }
}
