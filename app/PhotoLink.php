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
        // 只替換 <img src="https://www.xxxx.chc.edu.tw" 中的 www.
		return preg_replace(
			'/(<img\s+[^>]*?)src=["\']https?:\/\/www\.([a-z]{4}\.chc\.edu\.tw[^"\']*)["\']([^>]*>)/i',
			'$1src="https://$2"$3',
			$value
		);
    }    
}
