<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Group::truncate();  //清空資料庫
        $data = [
            ['name'=>'行政人員'],
            ['name'=>'級任老師'],
            ['name'=>'科任老師'],
            ['name'=>'其他職員'],
        ];

        \App\Group::insert($data);
    }
}
