<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 1)->create([
            'id' => 2,
            'company_id' => 2,
        ]);

        factory(\App\Models\User::class, 1)->create([
            'id' => 3,
            'company_id' => 2,
        ]);

        factory(\App\Models\User::class, 1)->create([
            'id' => 5,
            'company_id' => 3,
        ]);
        
        factory(\App\Models\User::class, 1)->create([
            'id' => 6,
            'company_id' => 3,
        ]);

        factory(\App\Models\User::class, 1)->create([
            'id' => 1,
            'company_id' => 1,
        ]);


        DB::table('user_grouping')->insert([
            'user_id' => 2,
            'user_group_id' => 2,
        ]);
        DB::table('user_grouping')->insert([
            'user_id' => 3,
            'user_group_id' => 2,
        ]);
        DB::table('user_grouping')->insert([
            'user_id' => 5,
            'user_group_id' => 2,
        ]);
        DB::table('user_grouping')->insert([
            'user_id' => 6,
            'user_group_id' => 2,
        ]);
        DB::table('user_grouping')->insert([
            'user_id' => 1,
            'user_group_id' => 1,
        ]);

    }
}
