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
            'num'=>'2',
        ]);
        \App\SetupCol::create([
            'num'=>'10',
        ]);
    }
}
