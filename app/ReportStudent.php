<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportStudent extends Model
{
    protected $fillable = [
        'semester',
        'name',
        'started_at',
        'stopped_at',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(ReportStudentItem::class);
    }
}
