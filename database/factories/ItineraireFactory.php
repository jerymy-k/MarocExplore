<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItineraireFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(3),
            'categorie' => $this->faker->randomElement(['plage', 'montagne', 'riviere', 'monument']),
            'duree' => $this->faker->numberBetween(1, 14),
            'image' => 'itineraires/test.jpg',
            'user_id' => User::factory(),
        ];
    }
}
