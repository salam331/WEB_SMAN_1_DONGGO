<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassRoom>
 */
class ClassRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['XII IPA 1', 'XII IPA 2', 'XII IPS 1', 'XI IPA 1', 'XI IPS 1', 'X IPA 1', 'X IPS 1']),
            'level' => $this->faker->randomElement(['10', '11', '12']),
            'capacity' => $this->faker->numberBetween(20, 40),
        ];
    }
}
