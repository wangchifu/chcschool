<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherAbsent extends Model
{
    protected $fillable = [
        'semester',
        'day',
        'hour',
        'user_id',
        'reason',
        'abs_kind',
        'place',
        'class_dis',
        'class_file',
        'month',
        'start_date',
        'end_date',
        'status',
        'deputy_user_id',
        'deputy_date',
        'check1_user_id',
        'check1_date',
        'check2_user_id',
        'check2_date',
        'check3_user_id',
        'check3_date',
        'check4_user_id',
        'check4_date',
        'note',
        'note_file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teacher_absent_outlays()
    {
        return $this->hasMany(TeacherAbsentOutlay::class);
    }
}
