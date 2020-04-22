<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClubSemester extends Model
{
    protected $fillable = [
        'semester',
        'start_date',
        'stop_date',
        'club_limit',
        'start_date2',
        'stop_date2',
    ];
}
