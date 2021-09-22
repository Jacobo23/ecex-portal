<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(['type' => 'user', 'name' => 'Juan' , 'email' => 'jakodev@outlook.com' , 'email_verified_at' => NULL , 'password' => '$2y$10$xHQJSabMghHVkS37VBJPbu3V.5rHB2achJq/fiGO6SFGxmFHK6nUK' , 'permits' => 'del_income,del_outcome,edit_part_number,edit_customer' , 'customer_ids' => NULL]);
        DB::table('users')->insert(['type' => 'customer', 'name' => 'Jacobo' , 'email' => 'jc_jacobo@outlook.com' , 'email_verified_at' => NULL , 'password' => '$2y$10$yfh9nBq0muO4OT7b/JcCBOwtsn5oppLcIzv7Xmw.LyDMiMMchCNKC' , 'permits' => '' , 'customer_ids' => '18']);
    }
}
