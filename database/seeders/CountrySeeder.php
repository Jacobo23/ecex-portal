<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert(['name' => 'Estados Unidos', 'short' => 'USA']);
        DB::table('countries')->insert(['name' => 'Mexico', 'short' => 'MEX']);
        DB::table('countries')->insert(['name' => 'Colombia', 'short' => 'COL']);
    }
}
