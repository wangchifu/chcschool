<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchOrder extends Model
{
    protected $fillable = [
        'name',
        'semester',
        'disable',
        'rece_name',
        'rece_date',
        'rece_num',
        'order_ps',
    ];
    public function lunch_order_dates()
    {
        return $this->hasMany(LunchOrderDate::class);
    }
}
