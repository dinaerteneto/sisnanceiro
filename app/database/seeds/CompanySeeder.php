<?php

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name' => 'Dinaerte',
            'last_name' => 'Neto',
            'company_name' => 'SiSnanceiro',
            'email' => 'webmaster@sisnanceiro.com.br',
        ]);

        DB::table('companies')->insert([
            'name' => 'Marcus',
            'last_name' => 'Adolfi',
            'company_name' => 'Crossx',
            'email' => 'marcus.adolfi@yahoo.com.br',
        ]);

        DB::table('companies')->insert([
            'name' => 'Rodrigo',
            'last_name' => 'Priolo',
            'company_name' => 'Smartside',
            'email' => 'contato@smartside.com.br',            
        ]);
    }
}
