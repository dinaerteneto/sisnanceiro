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
        \DB::table('person_address_type')->insert([
            'name' => 'Residencial',
            'nmae' => 'Comercial',
            'name' => 'Entrega',
            'name' => 'Correspondência',
            'name' => 'Outros',
        ]);
    }
}
