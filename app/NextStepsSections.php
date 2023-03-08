<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NextStepsSections extends Model
{
    protected $table = 'nextsections';
    protected $fillable = [
        'section_title',
        'slider_title',
        'created_at',
        'updated_at',
    ];
}
