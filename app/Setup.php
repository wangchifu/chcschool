<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    protected $fillable = [
        'site_name',
        'fixed_nav',
        'nav_color',
        'bg_color',
        'photo_link_number',
        'post_show_number',
        'title_image',
        'views',
        'footer',
        'ip1',
        'ip2',
        'ipv6',
        'all_post',
        'close_website',
        'homepage_name',
        'post_name',
        'openfile_name',
        'department_name',
        'schoolexec_name',
        'setup_name',
    ];
}
