<?php

namespace Modules\Judgments\Entities;

use Illuminate\Database\Eloquent\Model;

class Judgment extends Model
{
    protected $fillable = ["name",
        "description",
        "image",
        "document",
        "category_ids",
        "judge_name",
        "court_type",
        "date",
        "status"];
}
