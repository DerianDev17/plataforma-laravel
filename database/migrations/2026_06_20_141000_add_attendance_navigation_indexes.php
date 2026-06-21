<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->index(['deleted_at', 'created_at'], 'attendances_deleted_at_created_at_index');
            $table->index(['user_id', 'deleted_at', 'created_at'], 'attendances_user_id_deleted_at_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            if (! Schema::hasIndex('attendances', 'attendances_user_id_foreign')) {
                $table->index('user_id', 'attendances_user_id_foreign');
            }
        });

        Schema::table('attendances', function (Blueprint $table): void {
            if (Schema::hasIndex('attendances', 'attendances_user_id_deleted_at_created_at_index')) {
                $table->dropIndex('attendances_user_id_deleted_at_created_at_index');
            }

            if (Schema::hasIndex('attendances', 'attendances_deleted_at_created_at_index')) {
                $table->dropIndex('attendances_deleted_at_created_at_index');
            }

            if (Schema::hasIndex('attendances', 'attendances_user_id_created_at_index')) {
                $table->dropIndex('attendances_user_id_created_at_index');
            }

            if (Schema::hasIndex('attendances', 'attendances_created_at_index')) {
                $table->dropIndex('attendances_created_at_index');
            }
        });
    }
};
