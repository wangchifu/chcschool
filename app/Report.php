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
}
