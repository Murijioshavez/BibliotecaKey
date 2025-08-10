<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'isbn' => $this->faker->isbn13(),
            'available_copies' => $this->faker->numberBetween(1, 10),
            'category' => $this->faker->randomElement(['comedia', 'Ingenieria', 'Matematicas', 'Historia']),
            'cover_path' => $this->faker->imageUrl(200, 300),
            'description' => $this->faker->paragraph(3),

        ];
    }
}
