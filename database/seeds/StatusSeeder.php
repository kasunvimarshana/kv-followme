<?php

use Illuminate\Database\Seeder;

use App\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Status::create([
            'id' => 1,
            'name' => 'DEFAULT',
            'is_visible' => 1
        ]);
        
        Status::create([
            'id' => 2,
            'name' => 'OPEN',
            'is_visible' => 1
        ]);
        
        Status::create([
            'id' => 3,
            'name' => 'CLOSE',
            'is_visible' => 1
        ]);
    }
}
