<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('countries')->insert([
            [
                'name' => 'Nigeria',
                'iso_code' => 'NG',
                'calling_code' => '+234',
            ],
            [
                'name' => 'Ghana',
                'iso_code' => 'GH',
                'calling_code' => '+233',
            ],

        ]);
    }
}
