<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RssFeed extends Model
{
    protected $fillable = [
        'title',
        'url',
        'type',
        'num',
    ];
}
