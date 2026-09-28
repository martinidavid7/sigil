<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Secretary>
 */
class SecretaryFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('pt_BR');

        return [
            'name' => 'Secretaria de '.$faker->unique()->randomElement([
                'Educação', 'Saúde', 'Obras', 'Finanças', 'Administração',
                'Assistência Social', 'Meio Ambiente', 'Cultura', 'Esportes', 'Agricultura',
            ]),
            'responsible_name' => $faker->name(),
            'phone' => $faker->numerify('(##) 3###-####'),
            'address' => $faker->streetName(),
            'number' => $faker->buildingNumber(),
            'neighborhood' => 'Centro',
            'zip_code' => $faker->postcode(),
        ];
    }
}
