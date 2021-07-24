<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert(['name' => 'Cliente 1', 'email' => 'jc_jacobo@outlook.com']);
        DB::table('customers')->insert(['name' => 'Cliente 2', 'email' => 'jc_jacobo@outlook.com']);
        DB::table('customers')->insert(['name' => 'Cliente 3', 'email' => 'jc_jacobo@outlook.com']);
        DB::table('customers')->insert(['name' => 'Cliente 4', 'email' => 'jc_jacobo@outlook.com']);
    }
}
