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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('AdherentId');
            $table->unsignedBigInteger('EntrepriseId');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('SuperAdmin');
            $table->boolean('Status')->default(1);
            $table->boolean('Supprimer')->default(0);
            $table->datetime('Supp_util')->nullable();
             $table->datetime('Modif_util')->nullable();
            $table->string('Create_user')->nullable();
            $table->string('Edit_user')->nullable();
            $table->string('Delete_user')->nullable();
            $table->string('password');
          
            $table->string('ImageUser');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('AdherentId')->references('id')->on('adherents')->onDelete('cascade');
            $table->foreign('EntrepriseId')->references('id')->on('entreprises')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
