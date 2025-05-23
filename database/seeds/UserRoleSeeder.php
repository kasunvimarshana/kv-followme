<?php

use Illuminate\Database\Seeder;

use App\UserRole;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        UserRole::create([
            'user_pk' => 'kasunv@kv.net',
            'role_pk' => 'super-admin'
        ]);
    }
}
