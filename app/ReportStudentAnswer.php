<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportStudentAnswer extends Model
{
    protected $fillable = [
        'student_id',
        'user_id',
        'report_student_item_id',
        'report_student_id',        
    ];
    public function report_student_item()
    {
        return $this->belongsTo(ReportStudentItem::class);
    }
    public function report_student()
    {
        return $this->belongsTo(ReportStudent::class);
    }
}
