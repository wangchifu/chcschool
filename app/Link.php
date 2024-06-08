<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'icon',
        'type_id',
        'name',
        'url',
        'order_by',
        'target',
    ];
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
