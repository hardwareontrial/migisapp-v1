<?php

namespace Database\Seeders;

use App\Models\API\User\AppUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('app_users')->count() == 0){
            $users = [
                [
                    'nik' => '9000901',
                    'name' => 'ADMINISTRATOR',
                    'dept_id' => null,
                    'position_id' => null,
                    'group_id' => null,
                    'avatar' => null,
                    'active' => 1,
                    'created_at' => '2022-03-01 07:01:01',
                    'updated_at' => '2022-03-01 07:01:01',
                ],
                [
                    'nik' => '7000701',
                    'name' => 'ADMIN OKM',
                    'dept_id' => null,
                    'position_id' => null,
                    'group_id' => null,
                    'avatar' => null,
                    'active' => 1,
                    'created_at' => '2022-12-01 17:01:01',
                    'updated_at' => '2022-12-01 17:01:01',
                ]
            ];
            AppUser::insert($users);
        }else{
            echo 'Table not empty';
        }
    }
}
