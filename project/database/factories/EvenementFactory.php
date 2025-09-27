<?php

namespace Database\Factories;

use App\Models\Evenement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evenement>
 */
class EvenementFactory extends Factory
{
    protected $model = Evenement::class;

    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'date' => $this->faker->dateTimeBetween('+1 days', '+6 months'),
            'lieu' => $this->faker->city(),
            'type' => $this->faker->randomElement(['conference', 'atelier', 'webinaire', 'meetup']),
            'tarif' => $this->faker->randomFloat(2, 0, 500),
            'statut' => $this->faker->randomElement(['brouillon', 'public', 'annule']),
        ];
    }
}
