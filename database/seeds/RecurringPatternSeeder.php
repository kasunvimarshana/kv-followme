<?php

use Illuminate\Database\Seeder;

use App\RecurringType;
use App\RecurringPattern;

class RecurringPatternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default = RecurringType::where('name','default')->first();
        $general = RecurringType::where('name','general')->first();
        $twOwner = RecurringType::where('name','tw-owner')->first();
        $twOwnerHOD = RecurringType::where('name','tw-owner-hod')->first();
        //
        RecurringPattern::create([
            'recurring_type_id' => $default->id,
            'is_visible' => 0
        ]);
        
        RecurringPattern::create([
            'recurring_type_id' => $general->id,
            'is_visible' => 0
        ]);
        
        RecurringPattern::create([
            'recurring_type_id' => $twOwner->id,
            'is_visible' => 0
        ]);
        
        RecurringPattern::create([
            'recurring_type_id' => $twOwnerHOD->id,
            'is_visible' => 0
        ]);
    }
}
