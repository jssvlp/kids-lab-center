<?php

namespace Database\Seeders;

use App\Models\DadOrMom;
use App\Models\Parentt;
use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Child;

class ChildSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan = Plan::first();
        $parent = DadOrMom::first();
        $child = Child::create([
            'name' => 'Martin Joshua Velazquez',
            'birth_date' => '2016-12-13',
            'gender' =>'Niño',
            'dad_or_mom_id' => $parent->id,
            'plan_id' => $plan->id
        ]);

        $child = Child::create([
            'name' => 'Aisha Vasquez',
            'birth_date' => '2020-09-13',
            'gender' =>'Niña',
            'dad_or_mom_id' => 2,
            'plan_id' => $plan->id
        ]);

        $child = Child::create([
            'name' => 'Elen Vasquez',
            'birth_date' => '2020-09-13',
            'gender' =>'Niña',
            'dad_or_mom_id' => 2,
            'plan_id' => $plan->id
        ]);
        


    }
}
