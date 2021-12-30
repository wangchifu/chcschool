<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchClassDate extends Model
{
    protected $fillable = [
        'lunch_class_id',
        'order_date',
        'semester',
        'lunch_order_id',
        'lunch_factory_id',
        'eat_style',
        'eat_style1',
        'eat_style2',
        'eat_style3',
        'eat_style4',
    ];
    public function lunch_class()
    {
        return $this->belongsTo(LunchClass::class);
    }
    public function lunch_order()
    {
        return $this->belongsTo(LunchOrder::class);
    }
    public function lunch_factory()
    {
        return $this->belongsTo(LunchFactory::class);
    }
}
