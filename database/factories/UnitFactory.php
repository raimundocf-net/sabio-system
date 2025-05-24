<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'UBS ' . $this->faker->company,
            'municipality' => $this->faker->city,
            'cnes' => (string) $this->faker->unique()->numberBetween(1000000, 9999999),
        ];
    }
}
