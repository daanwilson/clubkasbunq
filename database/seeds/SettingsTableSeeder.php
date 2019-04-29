<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'key' => 'jeugdlid_functie_id',
            'value' => '1',
        ]);
        DB::table('settings')->insert([
            'key' => 'payment_request_surcharge',
            'value' => '0.50',
        ]);
    }
}
