<?php

namespace App;

use App\Student;
use Illuminate\Database\Eloquent\Model;
use App\Item;
use App\Action;

class StudentSign extends Model
{
    protected $fillable = [        
        'item_id',
        'item_name',
        'game_type',
        'is_official',
        'group_num',//同一項目不同組
        'student_id',
        'action_id',
        'student_year',
        'student_class',
        'num',        
        'sex',
        'achievement',
        'ranking',
        'order',
        'section_num',//預賽組別
        'run_num',//道次 出場順序
    ];

    public function student()
    {
        return $this->belongsTo(ClubStudent::class);
    }

    public function get_student_class()
    {
        return $this->belongsTo(StudentClass::class,'student_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function action()
    {
        return $this->belongsTo(Action::class);
    }
}
