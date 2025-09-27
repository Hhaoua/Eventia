<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Participant;

class ParticipantSeeder extends Seeder
{
    public function run(): void
    {
        Participant::factory()->count(20)->create();
    }
}
