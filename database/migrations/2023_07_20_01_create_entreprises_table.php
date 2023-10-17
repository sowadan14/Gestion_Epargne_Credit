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
        Schema::create('entreprises', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->string('Libelle');
            $table->string('Email');
            $table->string('Adresse')->nullable();
            $table->string('Telephone')->nullable();
            $table->boolean('Supprimer')->default(0);
            $table->integer('TVA')->default(18);
            $table->integer('Remise')->default(0);
            $table->string('Devise')->default('F CFA');
            $table->datetime('Supp_util')->nullable();
             $table->datetime('Modif_util')->nullable();
            $table->string('Create_user')->nullable();
            $table->string('Edit_user')->nullable();
            $table->string('Delete_user')->nullable();
            $table->integer('Taille')->nullable();;
            $table->string('Police')->nullable();;
            $table->string('ColorEntete')->nullable();
            $table->string('ColorSidebar')->nullable();
            $table->string('ColorFont')->nullable();
            $table->string('LogoEntreprise')->nullable();
            $table->string('EmailNotification')->nullable();
            $table->string('PasswordNotification')->nullable();
            $table->string('Pays')->nullable();
            $table->string('Ville')->nullable();
            $table->string('CodePostal')->nullable();
           
            $table->string('Nom');
            $table->string('NomReduit');
            $table->string('Code')->nullable();
            // $table->datetime('DateCreation');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
