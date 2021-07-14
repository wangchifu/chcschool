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
        'views',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
