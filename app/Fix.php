<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fix extends Model
{
    protected $fillable = [
        'type',
        'user_id',
        'title',
        'content',
        'reply',
        'situation',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
