<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteRequestServices extends Model
{
    protected $table = 'quote_request_services';
    protected $fillable = ['quote_request_id','service_id'];
    public function servicedetails() {
        return $this->hasOne('Modules\Services\Entities\Services', 'id','service_id');
    }
}
