<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PageFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(4);
        return [
            'title'        => $title,
            'slug'         => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'body'         => $this->faker->paragraphs(3, true),
            'cover_image'  => null,
            'status'       => 'published',
            'publish_date' => null,
            'menu_id'      => null,
            'created_by'   => null,
            'updated_by'   => null,
        ];
    }
}
