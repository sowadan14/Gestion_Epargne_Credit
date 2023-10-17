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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('EntrepriseId');
            $table->unsignedBigInteger('CompteId');
            $table->unsignedBigInteger('TypeOperationId');
            $table->unsignedBigInteger('AdherentId');
            $table->string('Libelle');
            $table->decimal('Montant',25,2)->default(0);
            $table->date('Date');
            $table->timestamps();

            $table->foreign('AdherentId')
            ->references('id')
            ->on('adherents')->onDelete('cascade');

            $table->foreign('TypeOperationId')
            ->references('id')
            ->on('type_operations')->onDelete('cascade');

            $table->foreign('CompteId')
            ->references('id')
            ->on('comptes')->onDelete('cascade');

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
        Schema::dropIfExists('operations');
    }
};
