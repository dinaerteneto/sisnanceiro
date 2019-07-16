<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Este comando "desabilita" a proteção do método fill($data = []); nos models
        Model::unguard();
        
        $seeders = [
            'BankCategorySeeder',
            'PaymentMethodSeeder',
            'PersonAddressTypeSeeder',
            'PersonContactTypeSeeder',
            'UsersGroupSeeder',
            'UsersTableSeeder',
        ];
        if ($seeders) {
            foreach ($seeders as $seeder) {
                $this->call($seeder);
            }
        }
    }
}
