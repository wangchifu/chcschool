<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'name',
        'order_by',
        'type_id',
    ];

    public function links()
    {
        return $this->hasMany(Link::class);
    }
}
