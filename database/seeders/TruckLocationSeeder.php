<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruckLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('truck_locations')->insert(['name' => 'Almacen 1 Patio']);
        DB::table('truck_locations')->insert(['name' => 'Almacen 1 Rampa']);
        DB::table('truck_locations')->insert(['name' => 'Almacen 2 Patio']);
        DB::table('truck_locations')->insert(['name' => 'Almacen 2 Rampa']);
    }
}
