<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchStu extends Model
{
    protected $fillable = [
        'semester',
        'no',
        'class_num',
        'name',
        'sex',
    ];
}
