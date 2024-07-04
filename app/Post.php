<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title_image',
        'title',
        'content',
        'job_title',
        'user_id',
        'views',
        'insite',
        'inbox',
        'top',
        'top_date',
        'die_date',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post_type()
    {
        return $this->belongsTo(PostType::class);
    }
}
