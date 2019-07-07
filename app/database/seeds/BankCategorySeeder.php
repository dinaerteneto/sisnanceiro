<?php

use Illuminate\Database\Seeder;

class BankCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('bank_category')->insert([
            ['name' => 'Saldo Inicial', 'status' => 1],
            ['name' => 'Contas a pagar', 'status' => 1],
            ['name' => 'Contas a receber', 'status' => 1],
        ]);
    }
}
