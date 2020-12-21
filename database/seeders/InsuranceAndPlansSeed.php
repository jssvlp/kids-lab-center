<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insurance;
use App\Models\Plan;

class InsuranceAndPlansSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insurance = Insurance::create(['name' => 'Ars Metasalud','phone' =>'(809) 688-2020']);
        $insurance = Insurance::create(['name' => 'Ars Renacer','phone' =>'(809) 688-2020' ]);
        $insurance = Insurance::create(['name' => 'Mapfre Salud','phone' =>'(809) 688-2020']);


        $plan = Plan::create(['name' => 'Básico I', 'insurance_id' => 1]);
        $plan = Plan::create(['name' => 'Básico II', 'insurance_id' => 1]);
        $plan = Plan::create(['name' => 'Intermedio I', 'insurance_id' => 1]);
        $plan = Plan::create(['name' => 'Avanzado I', 'insurance_id' => 1]);
    }
}
