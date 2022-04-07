<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TitleImageDesc extends Model
{
    protected $fillable = [
        'image_name',
        'link',
        'title',
        'desc',
    ];
}
