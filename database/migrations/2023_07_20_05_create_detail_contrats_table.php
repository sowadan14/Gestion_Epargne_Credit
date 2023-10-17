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
        Schema::create('detail_contrats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('EntrepriseId');
            $table->unsignedBigInteger('AdherentId');
            $table->unsignedBigInteger('ContratId');
            $table->decimal('Montant',25,2)->default(0);
            $table->date('DateDebut');
            $table->date('DateFin');
            $table->string('Modalite');
            $table->timestamps();

            $table->foreign('EntrepriseId')
            ->references('id')
            ->on('entreprises')->onDelete('cascade');

            $table->foreign('AdherentId')
            ->references('id')
            ->on('adherents')->onDelete('cascade');

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
        Schema::dropIfExists('detail_contrats');
    }
};
