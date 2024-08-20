<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolMarquee extends Model
{
    protected $fillable = [
        'title',
        'start_date',
        'stop_date',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
