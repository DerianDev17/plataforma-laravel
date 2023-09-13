<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordedClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recorded_classes', function (Blueprint $table) {
            $table->id();
            $table->string('module_number');
            $table->string('group');
            $table->string('lenguaje_link')->nullable();
            $table->string('math_link')->nullable();
            $table->string('science_link')->nullable();
            $table->string('social_link')->nullable();
            $table->string('orientation_link')->nullable();
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
        Schema::dropIfExists('recorded_classes');
    }
}
