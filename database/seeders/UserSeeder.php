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
        DB::table('users')->insert(['type' => 'user', 'name' => 'Juan' , 'email' => 'jakodev@outlook.com' , 'email_verified_at' => NULL , 'password' => '$2y$10$xHQJSabMghHVkS37VBJPbu3V.5rHB2achJq/fiGO6SFGxmFHK6nUK' , 'permits' => 'del_income,del_outcome' , 'customer_ids' => NULL]);
    }
}
