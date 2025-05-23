<?php

use Illuminate\Database\Seeder;

use App\TWMeta;

class TWMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        TWMeta::create([
            'id' => 1,
            'meta_key' => 'RESOURCE_DIR',
            'meta_value' => 'attachments', //Storage::url('attachments'),
            'is_visible' => 1
        ]);
    }
}
