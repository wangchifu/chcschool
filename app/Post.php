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
        'top',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
