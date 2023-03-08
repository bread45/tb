<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainerEvent extends Model
{
    protected $table = 'trainer_events';
    
    protected $fillable = [
    'trainer_id',
    'title',
    'type',
    'format',
    'cost',
    'venue',
    'start_date',
    'end_date',
    'accept_promo',
    'description',
    'members_allowed',
    'is_recurring',
    'recurring_type',
    'recurring_end',
    'status'
];
}
