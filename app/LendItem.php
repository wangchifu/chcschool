<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LendItem extends Model
{
    protected $fillable = [
        'lend_class_id',
        'name',
        'num',
        'user_id',
        'enable',
        'ps',
        'lend_sections',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function lend_class()
    {
        return $this->belongsTo(LendClass::class,'lend_class_id','id');
    }

}
