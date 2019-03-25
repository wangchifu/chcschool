<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Module::truncate();  //清空資料庫

        \App\Module::create([
            'name'=>'公告系統',
            'active'=>1,
        ]);
        \App\Module::create([
            'name'=>'檔案庫',
            'active'=>1,
        ]);
        \App\Module::create([
            'name'=>'處室介紹',
            'active'=>1,
        ]);
        \App\Module::create([
            'name'=>'好站連結',
            'active'=>1,
        ]);
        \App\Module::create([
            'name'=>'校務行政',
            'active'=>1,
        ]);
        \App\Module::create([
            'name'=>'會議文稿',
            'active'=>1,
        ]);
        \App\Module::create([
            'name'=>'報修系統',
            'active'=>1,
        ]);

    }
}
