<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'name',
        'url',
        'type',//1目錄  2檔案  3雲端檔案
        'folder_id',
        'user_id',
        'job_title',
        'order_by',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
