<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherAbsentOutlay extends Model
{
    protected $fillable = [
        'teacher_absent_id',
        'outlay_date',
        'places',
        'remember',
        'outlay1',
        'outlay2',
        'outlay3',
        'outlay4',
        'outlay5',
        'outlay6',
        'outlay7',
        'outlay8',
    ];

    public function teacher_absent()
    {
        return $this->belongsTo(TeacherAbsent::class);
    }
}
