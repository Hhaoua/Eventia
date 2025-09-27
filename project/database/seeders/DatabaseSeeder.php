<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            \Database\Seeders\UtilisateurSeeder::class,
            \Database\Seeders\OrganisateurSeeder::class,
            \Database\Seeders\ParticipantSeeder::class,
            \Database\Seeders\EvenementSeeder::class,
            \Database\Seeders\PaiementSeeder::class,
        ]);
    }
}
