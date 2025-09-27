<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->cascadeOnDelete();
            $table->string('contact')->nullable();
            $table->string('adresse')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisateurs');
    }
};
