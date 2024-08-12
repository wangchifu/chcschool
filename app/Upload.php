<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'name',
        'type',
        'folder_id',
        'user_id',
        'job_title',
        'order_by',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
