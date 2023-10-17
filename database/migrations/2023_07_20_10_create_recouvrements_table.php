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
        Schema::create('recouvrements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('EntrepriseId');
            $table->unsignedBigInteger('ContratId');
            $table->unsignedBigInteger('CompteId');

            $table->string('Modalite');
            $table->date('Date');
            $table->decimal('Montant',25,2)->default(0);
            $table->timestamps();

            $table->foreign('EntrepriseId')
            ->references('id')
            ->on('entreprises')->onDelete('cascade');

            $table->foreign('CompteId')
            ->references('id')
            ->on('comptes')->onDelete('cascade');

            $table->foreign('ContratId')
            ->references('id')
            ->on('contrats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recouvrements');
    }
};
