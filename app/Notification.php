<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'notification_type',
        'title',
        'message',
        'is_read',
    ];

    public function quotebyfrom()
    {
        return $this->hasOne('Modules\Users\Entities\FrontUsers', 'id', 'from_user_id');
    }
    public function quotebyto()
    {
        return $this->hasOne('Modules\Users\Entities\FrontUsers', 'id', 'to_user_id');
    }
}
