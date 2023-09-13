<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('student_groups_id')->default(999);
            $table->string('resource_title', 150);
            $table->string('type')->nullable();
            $table->tinyInteger('order')->default(-1);
            $table->timestamps();

            $table->foreign('topic_id')
                ->references('id')
                ->on('course_program_topics')
                ->onDelete('cascade');

            $table->foreign('student_groups_id')
                ->references('id')
                ->on('student_groups')
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
        Schema::dropIfExists('topic_resources');
    }
}
