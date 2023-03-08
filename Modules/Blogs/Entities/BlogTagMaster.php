<?php

namespace Modules\Blogs\Entities;

use Illuminate\Database\Eloquent\Model;

class BlogTagMaster extends Model {

    protected $table = 'blog_tag_master';
    protected $fillable = ['name', 'status'];

}
