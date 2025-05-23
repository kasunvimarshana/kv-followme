<?php

use Illuminate\Database\Seeder;

use App\MeetingCategory;

class MeetingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        MeetingCategory::create([
            'name' => 'Morning meeting',
            'is_visible' => 1
        ]);
        MeetingCategory::create([
            'name' => 'Pre production meeting',
            'is_visible' => 1
        ]);
        MeetingCategory::create([
            'name' => 'Operations CFT',
            'is_visible' => 1
        ]);
        MeetingCategory::create([
            'name' => 'Main CFT (Ops review & works HR)',
            'is_visible' => 1
        ]);
        MeetingCategory::create([
            'name' => 'Supervisor Review meeting',
            'is_visible' => 1
        ]);
        MeetingCategory::create([
            'name' => 'OSR',
            'is_visible' => 1
        ]);
    }
}
