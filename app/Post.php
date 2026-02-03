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
        // 只替換 <img src="https://www.xxxx.chc.edu.tw" 中的 www.
		return preg_replace(
			'/(<img\s+[^>]*?)src=["\']https?:\/\/www\.([a-z]{4}\.chc\.edu\.tw[^"\']*)["\']([^>]*>)/i',
			'$1src="https://$2"$3',
			$value
		);
    }
}
