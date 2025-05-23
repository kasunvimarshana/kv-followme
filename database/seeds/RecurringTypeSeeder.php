<?php

use Illuminate\Database\Seeder;

use App\RecurringType;

class RecurringTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        RecurringType::create([
            'name' => 'default',
            'is_visible' => 0
        ]);
        
        RecurringType::create([
            'name' => 'general',
            'is_visible' => 0
        ]);
        
        RecurringType::create([
            'name' => 'tw-owner',
            'is_visible' => 0
        ]);
        
        RecurringType::create([
            'name' => 'tw-owner-hod',
            'is_visible' => 0
        ]);
    }
}
