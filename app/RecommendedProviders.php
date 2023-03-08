<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecommendedProviders extends Model
{
    protected $table = 'recommended_providers';
    protected $fillable = ['user_id','provider_id'];
     
    public function users() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers','id','provider_id');
    }
    public function customer() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers','id','user_id');
    }
}
