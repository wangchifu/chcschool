<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetupCol extends Model
{
    protected $fillable = [
        'title',
        'num',
        'order_by'
    ];
    public function blocks()
    {
        return $this->hasMany(Block::class);
    }
}
