/*!
1.先在 server 下安裝 sqlite3
  sudo apt-get install sqlite3
2.執行 sqlite3 ，建立資料庫
  sqlite3 chcschool.sqlite
3.將此檔案放置在一個 777 的目錄下
4.執行以下 sql ，建立資料表
  sqlites chcschool.sqlite < sqlite3.sql
  */;

CREATE TABLE `wrenches` (
                            `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                            `school` varchar(50) DEFAULT NULL,
                            `job_title` varchar(50) DEFAULT NULL,
                            `name` varchar(50) DEFAULT NULL,
                            `email` text CHARACTER DEFAULT NULL,
                            `content` text CHARACTER NOT NULL,
                            `reply` text CHARACTER NULL,
                            `show` tinyint DEFAULT NULL,
                            `created_at` timestamp DEFAULT NULL,
                            `updated_at` timestamp DEFAULT NULL
);

