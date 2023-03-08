<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainerServices extends Model
{
    protected $fillable = ['service_id','price','trainer_id','format','message','image', 'fromDate', 'toDate', 'fromTime', 'toTime'];
    
    public function trainer() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers','id','trainer_id');
    }
    public function service() {
        return $this->hasOne('App\Services','id','service_id');
    }
    public function orders() {
        return $this->hasMany('Modules\Orders\Entities\Orders','service_id','id');
    }
    public function recommended() {
        return $this->hasOne('App\RecommendedProviders','provider_id','trainer_id');
    }
}
