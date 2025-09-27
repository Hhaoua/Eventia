<?php

namespace Database\Factories;

use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Utilisateur>
 */
class UtilisateurFactory extends Factory
{
    protected $model = Utilisateur::class;

    public function definition(): array
    {
        $roles = ['participant', 'admin', 'organisateur'];
        $role = $this->faker->randomElement($roles);
        $password = 'password';
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'mot_de_passe' => Hash::make($password),
            'role' => $role,
        ];
    }
}
