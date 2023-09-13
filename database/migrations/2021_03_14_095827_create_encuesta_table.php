<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncuestaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encuestas', function (Blueprint $table) {
            $table->id();
            $table->string('score_mate');
            $table->string('score_lengua');
            $table->string('score_socila');
            $table->string('score_ciencias');
            $table->string('score_voca')->nullable();
            $table->string('frecuencia');
            $table->text('atencion');
            $table->string('satisfaccion');
            $table->text('recomendacion')->nullable();

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
        Schema::dropIfExists('encuestas');
    }
}
