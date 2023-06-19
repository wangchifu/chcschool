<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = [
        'no',
        'semester',
        'name',
        'contact_person',
        'telephone_num',
        'money',
        'people',
        'teacher_info',
        'start_date',
        'start_time',
        'place',
        'ps',
        'taking',
        'prepare',
        'year_limit',
        'class_id',
        'no_check',
    ];
}
