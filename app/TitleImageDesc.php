<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TitleImageDesc extends Model
{
    protected $fillable = [
        'order_by',
        'image_name',
        'link',
        'title',
        'desc',
        'disable',
    ];
}
