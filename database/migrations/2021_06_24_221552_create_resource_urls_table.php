<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_urls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_resource_id');
            $table->string('url', 500);
            $table->json('url_metadata')->nullable();
            $table->timestamps();

            $table->foreign('topic_resource_id')
                ->references('id')
                ->on('topic_resources')
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
        Schema::dropIfExists('resource_urls');
    }
}
