<?php

namespace Database\Seeders;

use App\Model\Role;
use App\User;
use Config;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('username', 'admin')->first();
        $superUserRole = Role::where('name', 'superuser')->first();
        if (!isset($user)) {
            $data = [
                'name' => 'Admin',
                'email' => Config::get('constants.ADMIN_DEFAULT_EMAIL') ?? 'admin@ekcms.com',
                'username' => 'admin',
                'password' => Hash::make('123admin@'),
                'password_resetted' => 1,
            ];

            $createdUser = User::create($data);

            $createdUser->roles()->attach($superUserRole->id);

        }


    }
}
