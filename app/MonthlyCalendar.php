<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlyCalendar extends Model
{
    protected $fillable = [
        'item_date',
        'item',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
