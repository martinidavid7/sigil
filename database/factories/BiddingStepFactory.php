<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BiddingStep>
 */
class BiddingStepFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake('pt_BR')->words(2, true)),
            'enabled' => true,
        ];
    }

    public function disabled(): static
    {
        return $this->state(['enabled' => false]);
    }
}
