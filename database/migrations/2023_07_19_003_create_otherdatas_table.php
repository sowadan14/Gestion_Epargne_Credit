<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherdatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('couleurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('text');
            $table->string('value');
        });

        Schema::create('fonts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('text');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('couleurs');

        Schema::dropIfExists('fonts');
    }
}
