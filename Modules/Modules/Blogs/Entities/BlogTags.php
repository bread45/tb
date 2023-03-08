<?php

namespace Modules\Blogs\Entities;

use Illuminate\Database\Eloquent\Model;

class BlogTags extends Model {

    protected $fillable = ['blog_id', 'tag_id'];

    public function tags_list() {
        return $this->belongsTo('Modules\Blogs\Entities\BlogTagMaster', 'tag_id', 'id');
    }

}
