<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClubBlack extends Model
{
    protected $fillable = [
        'semester',
        'no',
    ];

    public function club_student()
    {
        return $this->belongsTo(ClubStudent::class,'no','no');
    }
}
