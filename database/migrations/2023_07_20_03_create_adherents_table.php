<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('adherents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('EntrepriseId');
            $table->unsignedBigInteger('PostId');
            $table->string('Nom');
            $table->string('Sexe');
            $table->string('Prenom');
            $table->string('Telephone')->nullable();
            $table->string('Email');
            $table->string('Pays')->nullable();
            $table->string('Ville')->nullable();
            $table->string('Profession');
            $table->datetime('DateNaissance');
            $table->date('DateAdhesion');
            $table->string('Adresse');

            $table->boolean('Status')->default('0');
            $table->timestamps();

            $table->foreign('PostId')
            ->references('id')
            ->on('posts')->onDelete('cascade');
            $table->foreign('EntrepriseId')
            ->references('id')
            ->on('entreprises')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adherents');
    }
};
