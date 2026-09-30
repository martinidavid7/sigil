<?php

namespace Database\Factories;

use App\Models\Secretary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Professional>
 */
class ProfessionalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'secretary_id' => Secretary::factory(),
            'name' => fake('pt_BR')->name(),
            'role' => fake()->randomElement(['Assessor(a)', 'Analista', 'Procurador(a)', 'Pregoeiro(a)', 'Assistente administrativo']),
        ];
    }
}
