<?php

namespace Database\Seeders;

use App\Models\Vaccine;
use Illuminate\Database\Seeder;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vaccines = [
            ['name' =>'Toxoide Tetanico', 'price' => 1000],
            ['name' =>'Boostrix', 'price' => 2000],
            ['name' =>'Influenza', 'price' => 2000],
            ['name' =>'Hepatitis B', 'price' => 2000],
            ['name' =>'Pneumo 23', 'price' => 3500 ],
            ['name' =>'Meningococo AC', 'price' => 6000],
            ['name' =>'Triple Viral', 'price' => 1500],
            ['name' =>'Neumococo', 'price' => 5800],
            ['name' =>'Rotavirus', 'price' => 3500],
            ['name' =>'Hepatitis A', 'price' => 3000],
            ['name' =>'Varicela', 'price' => 3500],
            ['name' =>'Pentavalente', 'price' => 4000],
            ['name' =>'Hexavalente', 'price' => 4500],
        ];
        foreach ($vaccines as $key => $vaccine) {
            Vaccine::create($vaccine);
        }
    }
}
