<?php

namespace Database\Factories;

use App\Enums\BiddingType;
use App\Models\BiddingMode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bidding>
 */
class BiddingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => fake()->unique()->numberBetween(1, 999),
            'year' => (int) date('Y'),
            'subject' => 'Aquisição de '.fake('pt_BR')->words(3, true),
            'bidding_mode_id' => BiddingMode::factory(),
            'type' => BiddingType::PurchaseServices,
            'estimated_value' => 50000,
        ];
    }
}
