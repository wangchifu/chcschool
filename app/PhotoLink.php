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
        'user_id',
        'photo_type_id'
    ];
}
