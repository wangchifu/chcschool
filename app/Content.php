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

    public function getContentAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        // 移除 https://www.xxx.chc.edu.tw 的 www.
        return preg_replace('/https?:\/\/www\.([^\/]+\.chc\.edu\.tw)/', 'https://$1', $value);
    }
}
