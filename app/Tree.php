<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    protected $fillable = [
        'folder_id',
        'type',
        'name',
        'url',
        'order_by',
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
