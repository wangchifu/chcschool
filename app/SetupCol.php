<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetupCol extends Model
{
    protected $fillable = [
        'num',
    ];
    public function blocks()
    {
        return $this->hasMany(Block::class);
    }
}
