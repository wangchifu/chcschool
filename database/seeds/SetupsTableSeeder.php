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
            'footer'=>'<div class=\"container\">\r\n<div class=\"row\">\r\n<div class=\"col-md-6\">\r\n<h5>學校資訊</h5>\r\n\r\n<hr />統一編號：7711xxxx<br />\r\n機關代碼：376479xxxx<br />\r\n教育部六碼代碼：07xxxx<br />\r\nOID：2.16.886.111.90010.90003.10xxxx<br />\r\n反霸凌專線：04-755xxxx #23<br />\r\n憂鬱性侵性騷防治專線：04-755xxxx #25<br />\r\n電話：04-755xxxx&nbsp; &nbsp; 傳真：04-756xxxx<br />\r\n地址：彰化縣xx鎮xx路xx號<br />\r\n</div>\r\n\r\n<div class=\"col-md-3\">\r\n<h5>宣導網站</h5>\r\n\r\n<hr />\r\n<ul>\r\n	<li class=\"col-md-3\">yahoo</li>\r\n	<li class=\"col-md-3\">google</li>\r\n</ul>\r\n</div>\r\n\r\n<div class=\"col-md-3\">\r\n<h5>宣導網站</h5>\r\n\r\n<hr />\r\n<ul>\r\n	<li class=\"col-md-3\">yahoo</li>\r\n	<li class=\"col-md-3\">google</li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>',
        ]);
    }
}
