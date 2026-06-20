<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicates = DB::table('attendances')
            ->select('course_session_id', 'user_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('course_session_id', 'user_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $dup) {
            DB::table('attendances')
                ->where('course_session_id', $dup->course_session_id)
                ->where('user_id', $dup->user_id)
                ->where('id', '!=', $dup->keep_id)
                ->delete();
        }

        Schema::table('attendances', function (Blueprint $table) {
            $table->unique(['course_session_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique(['course_session_id', 'user_id']);
        });
    }
};
