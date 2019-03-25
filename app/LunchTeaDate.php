<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchTeaDate extends Model
{
    protected $fillable = [
        'order_date',
        'enable',
        'semester',
        'lunch_order_id',
        'user_id',
        'lunch_place_id',
        'lunch_factory_id',
        'eat_style',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
