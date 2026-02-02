<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'icon',
        'type_id',
        'name',
        'url',
        'order_by',
        'target',
    ];
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function getUrlAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        // 移除 https://www.xxx.chc.edu.tw 的 www.
        return preg_replace('/https?:\/\/www\.([^\/]+\.chc\.edu\.tw)/', 'https://$1', $value);
    }
}
