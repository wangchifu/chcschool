<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'title',
        'group_id',
        'content',
        'power',
        'views',
        'tags',
    ];
}
