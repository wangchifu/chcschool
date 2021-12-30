<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassroomOrder extends Model
{
    protected $fillable = [
        'classroom_id',
        'order_date',
        'section',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
