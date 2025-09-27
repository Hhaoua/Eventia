<?php

namespace Database\Factories;

use App\Models\Paiement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Paiement>
 */
class PaiementFactory extends Factory
{
    protected $model = Paiement::class;

    public function definition(): array
    {
        return [
            'montant' => $this->faker->randomFloat(2, 5, 1000),
            'mode' => $this->faker->randomElement(['carte', 'especes', 'virement', 'paypal']),
            'statut' => $this->faker->randomElement(['en_attente', 'effectue', 'echoue']),
            'date' => $this->faker->dateTimeBetween('-1 months', 'now'),
            'id_inscription' => null,
        ];
    }
}
