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
            ['name' => 'Vendas', 'status' => 1],
            ['name' => 'Transferências', 'status' => 1],
            ['name' => 'Transferências entrada', 'status' => 1],
            ['name' => 'Transferências saída', 'status' => 1],
            ['name' => 'Fatura do cartão', 'status' => 1],
            ['name' => 'Saldo da fatura anterior', 'status' => 1],
        ]);
    }
}
