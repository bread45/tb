<?php

namespace Modules\Casebooks\Entities;

use Illuminate\Database\Eloquent\Model;

class Casebook extends Model
{
    protected $fillable = ["judgement_id",
        "user_id",
        "casebook_title",
        "topice",
        "sub_topice",
        "judge_name",
        "status",
        "created_at"];
    
    public function Judgment() {
        return $this->hasOne('\Modules\Judgments\Entities\Judgment', 'id', 'judgement_id');
    }
    public function Users() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers','id','user_id');
    }
}
