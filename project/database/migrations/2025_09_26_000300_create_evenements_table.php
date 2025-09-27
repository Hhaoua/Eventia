<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->dateTime('date');
            $table->string('lieu');
            $table->string('type')->nullable();
            $table->decimal('tarif', 10, 2)->nullable();
            $table->string('statut')->default('brouillon');
            $table->string('shortDescription')->nullable();
            $table->string('category')->nullable();
            $table->integer('places_max')->default(0);
            $table->integer('inscrits_count')->default(0);
            $table->string('image_url')->nullable();
            $table->unsignedBigInteger('organisateur_id');
            $table->decimal('prix_total', 10, 2)->default(0);
            $table->decimal('taux_participation', 5, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
