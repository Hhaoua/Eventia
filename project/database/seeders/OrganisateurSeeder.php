<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organisateur;
use App\Models\Utilisateur;

class OrganisateurSeeder extends Seeder
{
    public function run(): void
    {
        // Link some organisateurs to existing utilisateurs with role organisateur or create new
        $utilisateurs = Utilisateur::where('role', 'organisateur')->get();
        if ($utilisateurs->isEmpty()) {
            $utilisateurs = Utilisateur::factory()->count(3)->state(['role' => 'organisateur'])->create();
        }
        foreach ($utilisateurs as $u) {
            Organisateur::factory()->create([
                'utilisateur_id' => $u->id,
            ]);
        }
    }
}
