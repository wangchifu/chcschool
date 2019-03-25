<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchSetup extends Model
{
    protected $fillable = [
        'semester',
        'die_line',
        'teacher_open',
        'disable',
        'all_rece_name',
        'all_rece_date',
        'all_rece_num',
    ];
}
