<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostType extends Model
{
    protected $fillable = [
        'name',
        'order_by',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
