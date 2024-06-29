<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'title',
        'new_title',
        'content',
        'order_by',
        'setup_col_id',
        'block_color',
        'block_position',
        'disable_block_line',
    ];

    public function setup_col()
    {
        return $this->belongsTo(SetupCol::class);
    }
}
