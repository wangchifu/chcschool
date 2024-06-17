<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    protected $fillable = [
        'site_name',
        'fixed_nav',
        'nav_color',
        'photo_link_number',
        'title_image',
        'views',
        'footer',
        'ip1',
        'ip2',
        'ipv6',
        'all_post',
        'close_website',
        'bg_color',
    ];
}
