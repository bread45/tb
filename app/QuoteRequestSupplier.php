<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteRequestSupplier extends Model
{
    protected $table = 'quote_request_suppliers';
    protected $fillable = [
    'quote_request_id',
    'supplier_id',
    'isArchive',
    'isQuote'
    ];

    public function quoteby() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers', 'id','supplier_id');
    }
}
