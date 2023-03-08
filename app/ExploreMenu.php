<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExploreMenu extends Model
{
    protected $table = 'explore_menu_items';
    protected $fillable = [
        'city',
        'state'
    ];
}
