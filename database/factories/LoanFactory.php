<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 100),
            'book_id' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->randomElement(['vencido', 'prestado', 'reservado']),
            'fecha_prestamo' => Carbon::today(),
            'fecha_vencimiento' => Carbon::tomorrow(),
        ];
    }
}
