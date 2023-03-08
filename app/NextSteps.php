<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NextSteps extends Model
{
    protected $table = 'next_steps';
    protected $fillable = [
        'icon',
        'title',
        'content',
        'button_1',
        'button_1_link',
        'button_2',
        'modal_title',
        'modal_content',
    ];
}
