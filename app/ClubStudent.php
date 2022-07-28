<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClubStudent extends Model
{
    protected $fillable = [
        'semester',
        'no',
        'name',
        'class_num',
        'pwd',
        'birthday',
        'parents_telephone',
        'sex',
        'disable',
    ];
}
