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
        'disable_right',
        'title_image',
        'title_image_style',
        'views',
        'footer',
        'ip1',
        'ip2',
        'ipv6',
        'all_post',
        'post_line_token',
        'post_line_bot_token',
        'post_line_group_id',
        'close_website',
        'homepage_name',
        'post_name',
        'openfile_name',
        'department_name',
        'schoolexec_name',
        'setup_name',
        'school_marquee_behavior',
        'school_marquee_direction',
        'school_marquee_scrollamount',
        'school_marquee_color',
        'school_marquee_width',
    ];

    public function getFooterAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        // 移除 https://www.xxx.chc.edu.tw 的 www.
        return preg_replace('/https?:\/\/www\.([^\/]+\.chc\.edu\.tw)/', 'https://$1', $value);
    }
}
