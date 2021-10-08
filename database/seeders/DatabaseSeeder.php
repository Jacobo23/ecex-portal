<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            BundleTypeSeeder::class,
            CarrierSeeder::class,
            MeasurementUnitSeeder::class,
            CustomerSeeder::class,
            //PartNumberSeeder::class,
            SupplierSeeder::class,
            CountrySeeder::class,
            RegimeSeeder::class,
            TruckLocationSeeder::class,
            UserSeeder::class,
            TestPartNumberSeeder::class,
            TestIncomeSeeder::class,
            //TestOutcomeSeeder::class,
            //TestOutcomeSeeder::class,
        ]);
    }
}
