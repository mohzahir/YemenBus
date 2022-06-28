<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::insert([
            ['key' => 'HAJ_PROGRAM_RS_DEPOSIT', 'value' => '100'],
            ['key' => 'OMRA_PROGRAM_RS_DEPOSIT', 'value' => '200'],
            ['key' => 'HAJ_SERVICE_RS_PRICE', 'value' => '200'],
            ['key' => 'OMRA_SERVICE_RS_PRICE', 'value' => '200'],
        ]);
    }
}
