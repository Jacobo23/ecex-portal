<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BundleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bundle_types')->insert(['desc' => 'pack', 'weight' => 1.0]);
        DB::table('bundle_types')->insert(['desc' => 'caja', 'weight' => 1.0]);
        DB::table('bundle_types')->insert(['desc' => 'tarima', 'weight' => 2.0]);
        DB::table('bundle_types')->insert(['desc' => 'rollo', 'weight' => 1.0]);        
    }
}
