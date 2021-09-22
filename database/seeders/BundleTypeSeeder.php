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
        DB::table('bundle_types')->insert(['desc' => 'Atados', 'weight' => 2]);
        DB::table('bundle_types')->insert(['desc' => 'Barras', 'weight' => 0.5]);
        DB::table('bundle_types')->insert(['desc' => 'Barriles', 'weight' => 5]);
        DB::table('bundle_types')->insert(['desc' => 'Bolsas', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Bote', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Bulto', 'weight' => 0]);
        DB::table('bundle_types')->insert(['desc' => 'Carton', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Carton KC', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Caja de Plastico', 'weight' => 3]);
        DB::table('bundle_types')->insert(['desc' => 'Caja de Madera', 'weight' => 3]);
        DB::table('bundle_types')->insert(['desc' => 'Carretes', 'weight' => 300]);
        DB::table('bundle_types')->insert(['desc' => 'Charola', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Cilindro', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Cubeta', 'weight' => 5]);
        DB::table('bundle_types')->insert(['desc' => 'Galon', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Flete', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Jabas', 'weight' => 45]);
        DB::table('bundle_types')->insert(['desc' => 'Maleta', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Paquete', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Rollo', 'weight' => 0]);
        DB::table('bundle_types')->insert(['desc' => 'Saco', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Tanque', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Tarima', 'weight' => 35]);
        DB::table('bundle_types')->insert(['desc' => 'Tina de carton', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Tubo', 'weight' => 1]);
        DB::table('bundle_types')->insert(['desc' => 'Sobre', 'weight' => 0]);     
    }
}
