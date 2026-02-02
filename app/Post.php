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

    public function getContentAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        // 移除 https://www.xxx.chc.edu.tw 的 www.
        return preg_replace('/https?:\/\/www\.([^\/]+\.chc\.edu\.tw)/', 'https://$1', $value);
    }
}
