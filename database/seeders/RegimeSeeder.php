<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regimes')->insert(['name' => 'IN']);
        DB::table('regimes')->insert(['name' => 'NA']);
        DB::table('regimes')->insert(['name' => 'VN']);
    }
}
