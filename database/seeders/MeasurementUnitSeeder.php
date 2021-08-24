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
        DB::table('measurement_units')->insert(['id' => 1,'desc' => 'Pieza']);
        DB::table('measurement_units')->insert(['id' => 2,'desc' => 'Botella']);
        DB::table('measurement_units')->insert(['id' => 3,'desc' => 'Caja']);
        DB::table('measurement_units')->insert(['id' => 4,'desc' => 'Pallet']);
        DB::table('measurement_units')->insert(['id' => 5,'desc' => 'Pulgada']);
        DB::table('measurement_units')->insert(['id' => 6,'desc' => 'Caja']);
        DB::table('measurement_units')->insert(['id' => 7,'desc' => 'Litro']);
        DB::table('measurement_units')->insert(['id' => 8,'desc' => 'CM^3']);
        DB::table('measurement_units')->insert(['id' => 9,'desc' => 'Pieza']);
        DB::table('measurement_units')->insert(['id' => 10,'desc' => 'Docena']);
        DB::table('measurement_units')->insert(['id' => 11,'desc' => 'Yarda']);
        DB::table('measurement_units')->insert(['id' => 12,'desc' => 'PIEZA']);
        DB::table('measurement_units')->insert(['id' => 13,'desc' => 'Metro Lineal']);
        DB::table('measurement_units')->insert(['id' => 14,'desc' => 'Metro M3']);
        DB::table('measurement_units')->insert(['id' => 15,'desc' => 'GAL.']);
        DB::table('measurement_units')->insert(['id' => 16,'desc' => 'Gramo']);
        DB::table('measurement_units')->insert(['id' => 17,'desc' => 'C2']);
        DB::table('measurement_units')->insert(['id' => 18,'desc' => 'Metro Lineal']);
        DB::table('measurement_units')->insert(['id' => 19,'desc' => 'CM^3']);
        DB::table('measurement_units')->insert(['id' => 20,'desc' => 'Pieza']);
        DB::table('measurement_units')->insert(['id' => 21,'desc' => 'Kilo']);
        DB::table('measurement_units')->insert(['id' => 22,'desc' => 'Juego']);
        DB::table('measurement_units')->insert(['id' => 23,'desc' => 'Libra']);
        DB::table('measurement_units')->insert(['id' => 24,'desc' => 'Litro']);
        DB::table('measurement_units')->insert(['id' => 25,'desc' => 'Pieza']);
        DB::table('measurement_units')->insert(['id' => 26,'desc' => 'ME2']);
        DB::table('measurement_units')->insert(['id' => 27,'desc' => 'METROS']);
        DB::table('measurement_units')->insert(['id' => 28,'desc' => 'Militers']);
        DB::table('measurement_units')->insert(['id' => 29,'desc' => 'Litro']);
        DB::table('measurement_units')->insert(['id' => 30,'desc' => 'Kilo']);
        DB::table('measurement_units')->insert(['id' => 31,'desc' => 'Pieza']);
        DB::table('measurement_units')->insert(['id' => 32,'desc' => 'Paquete']);
        DB::table('measurement_units')->insert(['id' => 33,'desc' => 'PAR']);
        DB::table('measurement_units')->insert(['id' => 34,'desc' => 'Litro']);
        DB::table('measurement_units')->insert(['id' => 35,'desc' => 'Litro']);
        DB::table('measurement_units')->insert(['id' => 36,'desc' => 'Pieza']);
        DB::table('measurement_units')->insert(['id' => 37,'desc' => 'Pieza']);
        DB::table('measurement_units')->insert(['id' => 38,'desc' => 'Juego']);
        DB::table('measurement_units')->insert(['id' => 39,'desc' => 'SHEETS']);
        DB::table('measurement_units')->insert(['id' => 40,'desc' => 'Metro M2']);
        DB::table('measurement_units')->insert(['id' => 41,'desc' => 'Metro M2']);
        DB::table('measurement_units')->insert(['id' => 42,'desc' => 'TONELADA']);
        DB::table('measurement_units')->insert(['id' => 43,'desc' => 'Kilo']);
        DB::table('measurement_units')->insert(['id' => 44,'desc' => 'Pieza']);
        DB::table('measurement_units')->insert(['id' => 45,'desc' => 'Metro Lineal']);
        DB::table('measurement_units')->insert(['id' => 46,'desc' => 'YD2']);
        DB::table('measurement_units')->insert(['id' => 47,'desc' => 'FT']);
        DB::table('measurement_units')->insert(['id' => 48,'desc' => 'Onza']);
        DB::table('measurement_units')->insert(['id' => 49,'desc' => 'Pinta']);
        DB::table('measurement_units')->insert(['id' => 50,'desc' => 'Quart']);
        DB::table('measurement_units')->insert(['id' => 51,'desc' => 'Rollo']);

    }
}
