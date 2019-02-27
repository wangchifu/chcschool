<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'title',
        'content',
        'order_by',
        'setup_col_id',
    ];

    public function setup_col()
    {
        return $this->belongsTo(SetupCol::class);
    }
}
