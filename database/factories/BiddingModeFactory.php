<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BiddingMode>
 */
class BiddingModeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake('pt_BR')->unique()->word()),
            'deadline' => fake()->randomElement(['5 dias úteis', '8 dias úteis', '15 dias corridos']),
            'purchase_services_minimum_value' => 0,
            'purchase_services_maximum_value' => 176000,
            'construction_engineering_minimum_value' => 0,
            'construction_engineering_maximum_value' => 330000,
            'enabled' => true,
        ];
    }

    public function disabled(): static
    {
        return $this->state(['enabled' => false]);
    }
}
