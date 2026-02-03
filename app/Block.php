<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'title',
        'new_title',
        'content',
        'order_by',
        'setup_col_id',
        'block_color',
        'block_position',
        'disable_block_line',
    ];

    public function setup_col()
    {
        return $this->belongsTo(SetupCol::class);
    }

    public function getContentAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        // 只替換 <img src="https://www.xxxx.chc.edu.tw" 中的 www.
		return preg_replace(
			'/(<img\s+[^>]*?)src=["\']https?:\/\/www\.([a-z]{4}\.chc\.edu\.tw[^"\']*)["\']([^>]*>)/i',
			'$1src="https://$2"$3',
			$value
		);
    }    
}
