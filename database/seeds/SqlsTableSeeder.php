<?php

use Illuminate\Database\Seeder;

class SqlsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Sql::truncate();  //清空資料庫

        \App\Sql::create([
            'name'=>'2019-03-06_init.sql',
            'install'=>'1',
        ]);
    }
}
