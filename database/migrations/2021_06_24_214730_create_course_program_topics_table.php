<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseProgramTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_program_topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_program_id');
            $table->string('topic_title', 150);
            $table->text('topic_desc')->nullable();
            $table->tinyInteger('order')->default(-1);
            $table->timestamps();

            $table->foreign('course_program_id')
                ->references('id')
                ->on('courses_programs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_program_topics');
    }
}
