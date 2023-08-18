<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClubRegister extends Model
{
    protected $fillable = [
        'semester',
        'club_id',
        'club_student_id',
        'class_id',
        'second',
    ];
    public function user()
    {
        return $this->belongsTo(ClubStudent::class,'club_student_id','id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class,'club_id','id');
    }
}
