<?php

use Illuminate\Database\Seeder;

class SetupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Setup::truncate();  //清空資料庫

        \App\Setup::create([
            'site_name'=>'彰化縣xx國小全球資訊網',
            'title_image'=>'1',
            'views'=>'0',
        ]);
    }
}
