<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AppUserLoginTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('app_user_logins')->count() == 0){
            DB::table('app_user_logins')->insert([
                'nik' => 9000901,
                's_nik' => '901',
                'email' => 'admin@molindointigas.co.id',
                'password' => Hash::make('password'),
                'active' => 1,
                'admin' => null,
                'created_at' => '2022-03-01 07:01:01',
                'updated_at' => '2022-03-01 07:01:01',
            ],
            [
                'nik' => '7000701',
                's_nik' => '701',
                'email' => 'adminokm@migisapp.co.id',
                'password' => Hash::make('mig123!'),
                'active' => 1,
                'admin' => null,
                'created_at' => '2022-12-01 17:01:01',
                'updated_at' => '2022-12-01 17:01:01',
            ]);
        }else{
            echo 'Table not empty';
        }
    }
}
