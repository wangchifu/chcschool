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
        'eat_style_egg',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function lunch_order()
    {
        return $this->belongsTo(LunchOrder::class);
    }
    public function lunch_place()
    {
        return $this->belongsTo(LunchPlace::class);
    }
    public function lunch_factory()
    {
        return $this->belongsTo(LunchFactory::class);
    }
}
