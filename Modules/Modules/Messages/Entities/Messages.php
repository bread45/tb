<?php

namespace Modules\Messages\Entities;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $fillable = ['from_id','to_id','message','status'];
    public function FromUsers() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers','id','from_id');
    }
    public function ToUsers() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers','id','to_id');
    }
}
