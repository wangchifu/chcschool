<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(SetupsTableSeeder::class);
        $this->call(SetupColsTableSeeder::class);
        $this->call(BlocksTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(SqlsTableSeeder::class);
    }
}
