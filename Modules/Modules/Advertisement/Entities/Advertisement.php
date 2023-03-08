<?php

namespace Modules\Advertisement\Entities;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model {

    protected $table = 'advertisements';
    protected $fillable = [
        'name', 'start_date', 'end_date','image','amount','click_count','view','method','pageview','typeview','locations','url','status'
    ];
    
    function AdvertisementDetails(){
        return  $this->hasMany('Modules\Advertisement\Entities\AdvertisementDetails','advertisement_id','id');
    }
}
