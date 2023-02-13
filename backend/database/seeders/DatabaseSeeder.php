<?php

use Database\Seeders\AppUserLoginTableSeeder;
use Database\Seeders\AppUserTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AppUserLoginTableSeeder::class);
        $this->call(AppUserTableSeeder::class);
    }
}