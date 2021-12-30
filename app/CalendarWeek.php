<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalendarWeek extends Model
{
    protected $fillable = [
        'semester',
        'week',
        'start_end',
    ];
}
