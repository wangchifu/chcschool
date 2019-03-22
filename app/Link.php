<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'type_id',
        'name',
        'url',
        'order_by',
    ];
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
