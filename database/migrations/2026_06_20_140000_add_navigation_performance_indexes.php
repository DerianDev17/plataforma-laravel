<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table): void {
            $table->index('name', 'roles_name_index');
        });

        Schema::table('abilities', function (Blueprint $table): void {
            $table->index('name', 'abilities_name_index');
        });

        Schema::table('role_user', function (Blueprint $table): void {
            $table->index(['role_id', 'user_id'], 'role_user_role_id_user_id_index');
        });

        Schema::table('ability_role', function (Blueprint $table): void {
            $table->index(['ability_id', 'role_id'], 'ability_role_ability_id_role_id_index');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->index(['deleted_at', 'id'], 'users_deleted_at_id_index');
            $table->index(['payment_status', 'deleted_at'], 'users_payment_status_deleted_at_index');
            $table->index(['status', 'deleted_at'], 'users_status_deleted_at_index');
            $table->index(['student_group_id', 'deleted_at'], 'users_student_group_id_deleted_at_index');
        });

        Schema::table('course_sessions', function (Blueprint $table): void {
            $table->index(['date', 'time', 'student_groups_id'], 'course_sessions_date_time_group_index');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasIndex('users', 'users_student_group_id_foreign')) {
                $table->index('student_group_id', 'users_student_group_id_foreign');
            }
        });

        Schema::table('role_user', function (Blueprint $table): void {
            if (! Schema::hasIndex('role_user', 'role_user_role_id_foreign')) {
                $table->index('role_id', 'role_user_role_id_foreign');
            }
        });

        Schema::table('ability_role', function (Blueprint $table): void {
            if (! Schema::hasIndex('ability_role', 'ability_role_ability_id_foreign')) {
                $table->index('ability_id', 'ability_role_ability_id_foreign');
            }
        });

        Schema::table('course_sessions', function (Blueprint $table): void {
            if (Schema::hasIndex('course_sessions', 'course_sessions_date_time_group_index')) {
                $table->dropIndex('course_sessions_date_time_group_index');
            }
        });

        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasIndex('users', 'users_student_group_id_deleted_at_index')) {
                $table->dropIndex('users_student_group_id_deleted_at_index');
            }

            if (Schema::hasIndex('users', 'users_status_deleted_at_index')) {
                $table->dropIndex('users_status_deleted_at_index');
            }

            if (Schema::hasIndex('users', 'users_payment_status_deleted_at_index')) {
                $table->dropIndex('users_payment_status_deleted_at_index');
            }

            if (Schema::hasIndex('users', 'users_deleted_at_id_index')) {
                $table->dropIndex('users_deleted_at_id_index');
            }
        });

        Schema::table('ability_role', function (Blueprint $table): void {
            if (Schema::hasIndex('ability_role', 'ability_role_ability_id_role_id_index')) {
                $table->dropIndex('ability_role_ability_id_role_id_index');
            }
        });

        Schema::table('role_user', function (Blueprint $table): void {
            if (Schema::hasIndex('role_user', 'role_user_role_id_user_id_index')) {
                $table->dropIndex('role_user_role_id_user_id_index');
            }
        });

        Schema::table('abilities', function (Blueprint $table): void {
            if (Schema::hasIndex('abilities', 'abilities_name_index')) {
                $table->dropIndex('abilities_name_index');
            }
        });

        Schema::table('roles', function (Blueprint $table): void {
            if (Schema::hasIndex('roles', 'roles_name_index')) {
                $table->dropIndex('roles_name_index');
            }
        });
    }
};
