<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsideFile extends Model
{
    protected $fillable = [
        'name',
        'type',
        'folder_id',
        'user_id',
        'order_by',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
