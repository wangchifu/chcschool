<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fix extends Model
{
    protected $fillable = [
        'type',
        'user_id',
        'title',
        'content',
        'reply',
        'situation',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
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
