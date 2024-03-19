<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LendOrder extends Model
{
    protected $fillable = [
        'lend_item_id',
        'num',
        'lend_date',
        'lend_section',
        'back_date',
        'back_section',
        'user_id',
        'owner_user_id',
        'ps',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function owner_user()
    {
        return $this->belongsTo(User::class,'owner_user_id','id');
    }

    public function lend_item()
    {
        return $this->belongsTo(LendItem::class,'lend_item_id','id');
    }

}
