<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConversionFactorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('conversion_factor')->insert(['desc' => 'Libra', 'convert2' => 'Kilo', 'factor' => 0.453592]);
        DB::table('conversion_factor')->insert(['desc' => 'Kilo', 'convert2' => 'Libra', 'factor' => 2.20462]);
        DB::table('conversion_factor')->insert(['desc' => 'GAL.', 'convert2' => 'Litro', 'factor' => 3.78541]);
        DB::table('conversion_factor')->insert(['desc' => 'Litro', 'convert2' => 'GAL.', 'factor' => 0.264172]);
        DB::table('conversion_factor')->insert(['desc' => 'FT', 'convert2' => 'METROS', 'factor' => 0.3048]);
        DB::table('conversion_factor')->insert(['desc' => 'METROS', 'convert2' => 'FT', 'factor' => 3.28084]);
    }
}
