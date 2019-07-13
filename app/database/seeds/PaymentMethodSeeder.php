<?php

use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payment_method')->insert([
            ['name' => 'Cartão de débito'],
            ['name' => 'Cartão de crédito'],
            ['name' => 'Dinheiro'],
            ['name' => 'Cheque'],
            ['name' => 'Boleto bancário'],
            ['name' => 'Transferência'],
            ['name' => 'Cartão de crédito online'],
            ['name' => 'Boleto bancário online'],
            ['name' => 'Débito automático'],
            ['name' => 'Deposito'],
        ]);
    }
}
