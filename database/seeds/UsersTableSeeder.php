<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::truncate(); //清空資料庫
        \App\User::create([
            'username' => 'admin',
            'name' => '系統管理員',
            'password' => bcrypt('demo1234'),
            'admin'=>'1',
            'login_type'=>'local',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
    }
}
