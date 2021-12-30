<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoLink extends Model
{
    protected $fillable = [
        'name',
        'url',
        'image',
        'order_by',
    ];
}
