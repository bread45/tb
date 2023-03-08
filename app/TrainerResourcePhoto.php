<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainerResourcePhoto extends Model
{
    protected $table = 'trainer_resource_photo';
    protected $fillable = [
        'trainer_id',
        'image', 
        'is_featured'
    ];
}
