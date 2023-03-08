<?php

namespace Modules\StepManage\Entities;

use Illuminate\Database\Eloquent\Model;

class StepManage extends Model
{
    protected $table = 'stepmange';
    protected $fillable = ['title', 'number', 'type','description', 'image', 'order_by', 'status'];
}
