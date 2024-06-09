<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoType extends Model
{
    protected $fillable = [
        'name',
        'order_by',
    ];
}
