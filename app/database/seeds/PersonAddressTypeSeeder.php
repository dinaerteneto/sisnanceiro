<?php

use Illuminate\Database\Seeder;

class PersonAddressTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('person_address_types')->insert([
            ['name' => 'Residencial'],
            ['name' => 'Comercial'],
            ['name' => 'Outros'],
        ]);
    }
}
