<?php

namespace Modules\CMSPages\Entities;

use Illuminate\Database\Eloquent\Model;

class CMSPages extends Model
{
    protected $table = 'cms_pages';
    protected $fillable = [
    'title',
    'slug',
    'sub_title_text',
    'meta_title',
    'meta_keywords',
    'meta_description',
    'short_description',
    'description',
    'banner_image',
    'order_by',
    'status'];
}
