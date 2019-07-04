<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('person')->insert([
            'firstname' => 'Main Company',
        ]);

        \DB::table('company')->insert([
            'id' => 1,
        ]);

        \DB::table('person')->insert([
            'company_id' => 1,
            'firstname'  => 'Main User',
            'lastname'   => 'For Company',
            'email'      => 'main@sisnanceiro.com.br',
            'gender'     => 'M',
        ]);

        \DB::table('users')->insert([
            'company'  => 1,
            'email'    => 'main@sisnanceiro.com.br',
            'password' => bcrypt('secret'),
        ]);

        \DB::table('user_grouping')->insert([
            ['user_id' => 2, 'user_group_id' => 1],
            ['user_id' => 2, 'user_group_id' => 2],
            ['user_id' => 2, 'user_group_id' => 3],
        ]);

    }
}
