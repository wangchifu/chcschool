<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    protected $fillable = [
        'site_name',
        'nav_color',
        'title_image',
        'views',
        'footer',
    ];
}
