<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchClass extends Model
{
    protected $fillable = [
        'semester',
        'class_id',
        'class_name',
    ];
}
