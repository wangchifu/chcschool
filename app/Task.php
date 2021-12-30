<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'user_id',
        'disable',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
