<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\DadOrMom::factory(5)->create();
        $this->call(UserSeed::class);
        $this->call(InsuranceAndPlansSeed::class);
        $this->call(ChildSeed::class);
    }
}
