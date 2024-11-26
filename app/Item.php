<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'action_id',
        'order',
        'game_type',
        'official',
        'reserve',
        'name',
        'group',
        'type',
        'years',
        'limit',
        'people',
        'reward',
        'scoring',
        'disable',
    ];

    public function action()
    {
        return $this->belongsTo(Action::class);
    }
}
