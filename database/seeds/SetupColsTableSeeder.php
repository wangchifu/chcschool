<?php

use Illuminate\Database\Seeder;

class SetupColsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\SetupCol::truncate();  //清空資料庫

        \App\SetupCol::create([
            'title'=>'左欄',
            'num'=>'2',
            'order_by'=>'2',
        ]);
        \App\SetupCol::create([
            'title'=>'主要',
            'num'=>'10',
            'order_by'=>'3',
        ]);
        \App\SetupCol::create([
            'title'=>'榮譽榜',
            'num'=>'12',
            'order_by'=>'1',
        ]);

    }
}
