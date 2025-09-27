<?php

namespace Database\Factories;

use App\Models\Organisateur;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organisateur>
 */
class OrganisateurFactory extends Factory
{
    protected $model = Organisateur::class;

    public function definition(): array
    {
        return [
            'utilisateur_id' => Utilisateur::factory()->state(['role' => 'organisateur']),
            'contact' => $this->faker->phoneNumber(),
            'adresse' => $this->faker->address(),
        ];
    }
}
