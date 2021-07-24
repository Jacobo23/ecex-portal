<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('part_numbers')->insert(['part_number' => 'Part A','customer_id' => 1,'um' => 'Pieza','unit_weight' => 1.0,'desc_ing' => 'Part number 1','desc_esp' => 'Numero de parte 1','origin_country' => 'USA','fraccion' => '99999999','nico' => '99','brand' => '','model' => '','serial' => '','imex' => '','fraccion_especial' => '','regime' => '','warning' => false]);
        DB::table('part_numbers')->insert(['part_number' => 'Part B','customer_id' => 1,'um' => 'Pieza','unit_weight' => 1.5,'desc_ing' => 'Part number 2','desc_esp' => 'Numero de parte 2','origin_country' => 'MEX','fraccion' => '99999999','nico' => '99','brand' => 'marca 2','model' => 'modelo 2','serial' => 'serie123456789xx12','imex' => 'imex#1','fraccion_especial' => 'Este material es un quimico peligroso','regime' => 'IN','warning' => true]);
        DB::table('part_numbers')->insert(['part_number' => 'Part C','customer_id' => 1,'um' => 'Pieza','unit_weight' => 0.3,'desc_ing' => 'Part number 3','desc_esp' => 'Numero de parte 3','origin_country' => 'USA','fraccion' => '99999999','nico' => '99','brand' => '','model' => '','serial' => '','imex' => '','fraccion_especial' => '','regime' => '','warning' => false]);
        DB::table('part_numbers')->insert(['part_number' => 'Part D','customer_id' => 2,'um' => 'Pieza','unit_weight' => 1.0,'desc_ing' => 'Part number 4','desc_esp' => 'Numero de parte 4','origin_country' => 'USA','fraccion' => '99999999','nico' => '99','brand' => '','model' => '','serial' => '','imex' => '','fraccion_especial' => '','regime' => '','warning' => false]);
        DB::table('part_numbers')->insert(['part_number' => 'Part E','customer_id' => 2,'um' => 'Pieza','unit_weight' => 2.0,'desc_ing' => 'Part number 5','desc_esp' => 'Numero de parte 5','origin_country' => 'USA','fraccion' => '99999999','nico' => '99','brand' => '','model' => '','serial' => '','imex' => '','fraccion_especial' => '','regime' => '','warning' => false]);
    }
}
