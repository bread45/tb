<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteRequestConversation extends Model
{
    protected $table = 'quote_request_conversations';
    protected $fillable = [
        'user_id',
        'quote_request_id',
        'message',
        'include_cost',
        'cost_estimation',
        'organiser',
        'organising_event',
    ];
    
    public function quoteby() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers', 'id','user_id');
    }

    public function quoteRequestConversationMessages() {
        return $this->hasMany('App\QuoteRequestConversationMessage', 'qr_c_id','id')->orderBy('updated_at', 'asc');
    }

    public function quoteRequestConversationBucket() {
        return $this->hasOne('App\QuoteRequestConversationBucket', 'qr_c_id','id');
    }

}
