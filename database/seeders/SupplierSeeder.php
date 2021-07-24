<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('suppliers')->insert(['name' => 'Proveedor 1']);
        DB::table('suppliers')->insert(['name' => 'Proveedor 2']);
        DB::table('suppliers')->insert(['name' => 'Proveedor 3']);
        DB::table('suppliers')->insert(['name' => 'Proveedor 4']);
        DB::table('suppliers')->insert(['name' => 'Proveedor 5']);
    }
}
