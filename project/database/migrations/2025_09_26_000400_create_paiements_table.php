<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant', 10, 2);
            $table->string('mode');
            $table->string('statut')->default('en_attente');
            $table->dateTime('date');
            $table->unsignedBigInteger('id_inscription')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
