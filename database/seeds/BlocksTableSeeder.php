<?php

use Illuminate\Database\Seeder;

class BlocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Block::truncate();  //清空資料庫

        \App\block::create([
            'title'=>'最新公告(系統區塊)',
            'content'=>' ',
            'order_by'=>'1',
            'setup_col_id'=>'2',
        ]);

        \App\block::create([
            'title'=>'連結區塊',
            'content'=>'<ul><li><a href="http://boe.chc.edu.tw" target="_blank">教育處雲端</a></li><li><a href="http://school.chc.edu.tw" target="_blank">學校資料平台</a></li></ul>',
            'order_by'=>'1',
            'setup_col_id'=>'1',
        ]);
    }
}
