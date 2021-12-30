<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = [
        'calendar_week_id',
        'semester',
        'calendar_kind',
        'content',
        'user_id',
        'job_title',
        'order_by',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function calendar_week()
    {
        return $this->belongsTo(Calendar::class);
    }
}
