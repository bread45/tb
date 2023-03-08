<?php

namespace Modules\Advertisement\Entities;

use Illuminate\Database\Eloquent\Model;

class AdvertisementDetails extends Model {

    protected $table = 'advertisements_details';
    protected $fillable = [
        'advertisement_id', 'user_id', 'price','created_at'
    ];
    
    function Advertisement(){
        return  $this->hasOne('Modules\Advertisement\Entities\Advertisement','id','advertisement_id');
    }
    function user(){
        return  $this->hasOne('Modules\Users\Entities\FrontUsers','id','user_id');
    }

}
