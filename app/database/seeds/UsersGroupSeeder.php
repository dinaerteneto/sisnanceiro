<?php

use Illuminate\Database\Seeder;

class UsersGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('user_group')->insert([
            ['name' => 'master'],
            ['name' => 'admin'],
            ['name' => 'user'],
        ]);
    }
}
