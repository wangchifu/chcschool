<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchPlace extends Model
{
    protected $fillable = [
        'name',
        'disable',
    ];
}
