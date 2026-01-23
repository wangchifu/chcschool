<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportStudentItem extends Model
{
    protected $fillable = [
        'name',
        'report_student_id',   
    ];    
}
