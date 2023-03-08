<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteRequestConversationMessage extends Model
{
    protected $table = 'quote_request_conversation_messages';
    protected $fillable = [
        'qr_c_id',
        'user_id',
        'message',
    ];

    public function quoteby() {
        $userDetails = $this->hasOne('Modules\Users\Entities\FrontUsers', 'id','user_id');
        return $userDetails;
    }
    
}
