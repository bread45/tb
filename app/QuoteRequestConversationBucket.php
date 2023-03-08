<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteRequestConversationBucket extends Model
{
    protected $table = 'quote_request_conversation_buckets';
    protected $fillable = [
        'qr_c_id',
        'attachment',
    ];
}
