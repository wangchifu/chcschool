<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchFactory extends Model
{
    protected $fillable = [
        'name',
        'disable',
        'fid',
        'fpwd',
    ];
}
