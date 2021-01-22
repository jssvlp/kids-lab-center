<?php

namespace Database\Factories;

use App\Models\DadOrMom;
use Illuminate\Database\Eloquent\Factories\Factory;

class DadOrMomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DadOrMom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'kinship' => $this->faker->randomElement(['Madre','Padre'])
        ];
    }
}
