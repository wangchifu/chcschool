<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchSetup extends Model
{
    protected $fillable = [
        'semester',
        'eat_styles',
        'die_line',
        'teacher_open',
        'disable',
        'all_rece_name',
        'all_rece_date',
        'all_rece_no',
        'all_rece_num',
        'teacher_money',
    ];
}
