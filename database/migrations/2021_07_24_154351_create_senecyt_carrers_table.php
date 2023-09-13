<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSenecytCarrersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('senecyt_carrers', function (Blueprint $table) {
            $table->id();
            $table->string('institucion', 100);
            $table->string('carrera', 100);
            $table->string('campus', 50);
            $table->string('provincia', 30);
            $table->string('modalidad', 20);
            $table->string('jornada', 20)->default('sin dato');
            $table->integer('puntaje_referencial');
            $table->string('website', 100)->default('sin dato');
            $table->string('tipo_institucion', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('senecyt_carrers');
    }
}
