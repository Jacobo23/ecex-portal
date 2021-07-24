<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('measurement_units')->insert(['desc' => 'Pieza']);
        DB::table('measurement_units')->insert(['desc' => 'Bote']);
        DB::table('measurement_units')->insert(['desc' => 'EA']);
        DB::table('measurement_units')->insert(['desc' => 'Lt']);
        DB::table('measurement_units')->insert(['desc' => 'Kg']);
        DB::table('measurement_units')->insert(['desc' => 'Lb']);
    }
}
