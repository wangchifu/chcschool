<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPower extends Model
{
    protected $table = "user_powers";

    protected $fillable = [
        'user_id',
        'type',
        'name',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
