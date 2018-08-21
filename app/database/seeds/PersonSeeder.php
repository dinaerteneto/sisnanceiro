<?php

use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Person::class, 4)->create([
            'company_id' => 2
        ]);
        factory(\App\Models\Person::class, 4)->create([
            'company_id' => 3
        ]);
        factory(\App\Models\Person::class, 1)->create([
            'company_id' => 1
        ]);

    }
}
