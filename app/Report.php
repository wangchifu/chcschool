<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'job_title',
        'meeting_id',
        'content',
        'order_by',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
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
