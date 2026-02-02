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
        // 移除 https://www.xxx.chc.edu.tw 的 www.
        return preg_replace('/https?:\/\/www\.([^\/]+\.chc\.edu\.tw)/', 'https://$1', $value);
    }
}
