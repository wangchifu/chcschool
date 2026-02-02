<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoLink extends Model
{
    protected $fillable = [
        'name',
        'url',
        'image',
        'order_by',
        'user_id',
        'photo_type_id'
    ];

    public function getUrlAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        // 移除 https://www.xxx.chc.edu.tw 的 www.
        return preg_replace('/https?:\/\/www\.([^\/]+\.chc\.edu\.tw)/', 'https://$1', $value);
    }
}
