<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->text('ies_nombre_institut')->nullable();
            $table->text('ies_tipo_ies')->nullable();
            $table->text('ies_tipo_financiamiento')->nullable();
            $table->text('cam_direccion')->nullable();
            $table->text('provincia_campus')->nullable();
            $table->text('canton_campus')->nullable();
            $table->text('car_nombre_carrera')->nullable();
            $table->text('ofa_titulo')->nullable();
            $table->text('modalidad')->nullable();
            $table->text('jornada')->nullable();
            $table->text('apc_descripcion_carrera')->nullable();
            $table->text('cmc_duracion_carrera')->nullable();
            $table->text('ofa_id')->nullable();
            $table->text('puntaje_referencial')->nullable();
            $table->text('apc_perfil_ocupacional')->nullable();
            $table->text('porcentaje_beca')->nullable();
            $table->text('provincia_campus_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('careers');
    }
}
