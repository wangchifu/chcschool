## 彰化縣國中小學 校網代管系統
### 安裝
git clone https://github.com/wangchifu/chcschool.git
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

