<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    protected $fillable = [
        'folder_id',
        'type',
        'name',
        'url',
        'order_by',
    ];
}
