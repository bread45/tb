<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainerPhoto extends Model
{
    protected $table = 'trainer_photo';
    protected $fillable = [
        'trainer_id',
        'image', 
        'is_featured'
    ];
}
