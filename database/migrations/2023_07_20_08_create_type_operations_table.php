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
        Schema::create('type_operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('EntrepriseId');
            $table->string('Libelle');
            $table->timestamps();

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
        Schema::dropIfExists('type_operations');
    }
};
