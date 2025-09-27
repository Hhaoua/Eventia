<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;

class UtilisateurSeeder extends Seeder
{
    public function run(): void
    {
        Utilisateur::factory()->count(5)->create();
        // Ensure at least one admin and one organisateur
        Utilisateur::factory()->state(['role' => 'admin'])->create([
            'email' => 'admin@example.com',
        ]);
        Utilisateur::factory()->state(['role' => 'organisateur'])->create([
            'email' => 'orga@example.com',
        ]);
    }
}
