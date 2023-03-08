<?php

namespace Modules\GeneralSettings\Entities;

use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model {

    protected $table = 'general_settings';
    protected $fillable = ['attr_key', 'attr_value', 'status'];

}
