<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'       => $this->faker->words(2, true),
            'parent_id'  => null,
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
