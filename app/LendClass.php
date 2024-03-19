<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LendClass extends Model
{
    protected $fillable = [
        'name',
        'ps',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
