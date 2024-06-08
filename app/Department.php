<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'icon',
        'title',
        'content',
        'order_by',
        'target',
    ];
}
