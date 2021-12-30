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
            'footer'=>'<p style="text-align: center;">地址：彰化縣xx鎮xx路xx號&nbsp; &nbsp; 電話：04-xxxxxxx<br />
統一編號：77119xxx&nbsp; &nbsp;機關代碼：3764797xxx&nbsp; &nbsp; 教育部六碼代碼：074xxx<br />
OID：2.16.886.111.90010.90003.xxxxxx</p>',
        ]);
    }
}
