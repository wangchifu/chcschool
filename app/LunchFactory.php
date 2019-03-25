<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchFactory extends Model
{
    protected $fillable = [
        'name',
        'teacher_money',
        'disable',
        'fid',
        'fpwd',
    ];
}
