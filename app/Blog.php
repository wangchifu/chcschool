<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title_image',
        'title',
        'content',
        'user_id',
        'job_title',
        'views',
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
        // 只替換 <img src="https://www.xxxx.chc.edu.tw" 中的 www.
		return preg_replace(
			'/(<img\s+[^>]*?)src=["\']https?:\/\/www\.([a-z]{4}\.chc\.edu\.tw[^"\']*)["\']([^>]*>)/i',
			'$1src="https://$2"$3',
			$value
		);
    }    
}
