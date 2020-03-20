<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClubRegister extends Model
{
    protected $fillable = [
        'semester',
        'club_id',
        'club_student_id',
    ];
}
