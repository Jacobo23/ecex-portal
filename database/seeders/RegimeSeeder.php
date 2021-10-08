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
        DB::table('regimes')->insert(['name' => 'AF']);
        DB::table('regimes')->insert(['name' => 'A1']);
        DB::table('regimes')->insert(['name' => 'V1']);
        DB::table('regimes')->insert(['name' => 'R1']);
        DB::table('regimes')->insert(['name' => 'NA']);
        DB::table('regimes')->insert(['name' => 'DI']);
    }
}
