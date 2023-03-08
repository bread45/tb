<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderLocations extends Model
{
    protected $table = 'provider_locations';
    protected $fillable = ['user_id','location_id'];
       
    public function locations() {
        return $this->hasOne('Modules\Locations\Entities\Locations','id','location_id');
    }
}
