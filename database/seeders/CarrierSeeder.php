<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('carriers')->insert(['name' => 'Trans1']);
        DB::table('carriers')->insert(['name' => 'Trans2']);
        DB::table('carriers')->insert(['name' => 'Trans3']);
        DB::table('carriers')->insert(['name' => 'Trans4']);
        DB::table('carriers')->insert(['name' => 'Trans5']);
    }
}
