<?php

use Illuminate\Database\Seeder;

class PersonContactTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('person_contact_type')->insert([
            ['type' => 'E-Mail'],
            ['type' => 'Telefone celular'],
            ['type' => 'Telefone residencial'],
            ['type' => 'Telefone comercial'],
            ['type' => 'Telefone recados'],
            ['type' => 'Outros'],
        ]);
    }
}
