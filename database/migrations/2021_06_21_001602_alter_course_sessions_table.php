<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCourseSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('student_groups_id')->default(999);

            if (DB::getDriverName() !== 'sqlite') {
                if (Schema::hasColumn('course_sessions', 'course_id')) {
                    $table->dropForeign('course_sessions_course_id_foreign');
                }
                $table->foreign('student_groups_id')
                    ->references('id')
                    ->on('student_groups')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_sessions', function (Blueprint $table) {
            //
        });
    }
}
