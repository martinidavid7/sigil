<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CityHall>
 */
class CityHallFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('pt_BR');
        $city = $faker->city();

        return [
            'name' => "Prefeitura Municipal de {$city}",
            'mayor' => $faker->name(),
            'cnpj' => $faker->cnpj(),
            'state_registration' => $faker->numerify('###.###.###.###'),
            'address' => $faker->streetName(),
            'number' => $faker->buildingNumber(),
            'neighborhood' => $faker->randomElement(['Centro', 'Jardim América', 'Vila Nova', 'São José']),
            'city' => $city,
            'zip_code' => $faker->postcode(),
            'phone' => $faker->numerify('(##) 3###-####'),
        ];
    }
}
