## 彰化縣國中小學 校網代管系統方案三
### 安裝
git clone https://git.com/wangchifu/chcschool.git
進入目錄<br>
composer install<br>
cp .env.example .env<br>
.env 中 DB_DATABASE=chcschool<br>
.env 中 DB_USERNAME 及 DB_PASSWORD 填上正確資料<br>
php artisan key:generate<br>
php artisan storage:link<br>
sudo chmod 777 -R storage bootstrap/cache<br>
php artisan migrate<br>
php artisan db:seed<br>
新增資料庫名為 chcschool 編碼 utf8mb4_vietnamese_ci	<br>
新增該校代碼資料庫，如 s074xxx<br>
### 設計模組功能
編輯 ./config/chcschool.php<br>
    'modules'=>[<br>
        'posts'=>'公告系統',<br>
        'open_files'=>'檔案庫',<br>
        'links'=>'好站連結',<br>
        'schools'=>'校務行政',<br>
        'fixes'=>'報修系統',<br>
        'meetings'=>'會議文稿',<br>
    ],<br>

新增一組數據<br>
編輯 ./resource/views/layout/nav.blade.php 添加該模組的連結(參考其他模組 )
