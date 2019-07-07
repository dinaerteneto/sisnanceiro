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
            ['nmae' => 'Cartão de débito'],
            ['nmae' => 'Cartão de crédito'],
            ['nmae' => 'Dinheiro'],
            ['nmae' => 'Cheque'],
            ['nmae' => 'Boleto bancário'],
            ['nmae' => 'Transferência'],
            ['nmae' => 'Cartão de crédito online'],
            ['nmae' => 'Boleto bancário online'],
            ['nmae' => 'Débito automático'],
            ['nmae' => 'Deposito'],
        ]);
    }
}
