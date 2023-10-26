<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FixClass extends Model
{
    protected $fillable = [
        'name',
        'order_by',
        'disable',
    ];
}
