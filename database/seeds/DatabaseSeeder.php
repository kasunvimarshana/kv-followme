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
        // $this->call(UsersTableSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(MeetingCategorySeeder::class);
        $this->call(TWMetaSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(RecurringTypeSeeder::class);
        $this->call(RecurringPatternSeeder::class);
    }
}
