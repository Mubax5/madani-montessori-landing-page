<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_users', function (Blueprint $table): void {
            if (! Schema::hasColumn('admin_users', 'app_authentication_secret')) {
                $table->text('app_authentication_secret')->nullable()->after('remember_token');
            }

            if (! Schema::hasColumn('admin_users', 'app_authentication_recovery_codes')) {
                $table->text('app_authentication_recovery_codes')->nullable()->after('app_authentication_secret');
            }

            if (! Schema::hasColumn('admin_users', 'password_changed_at')) {
                $table->timestamp('password_changed_at')->nullable()->after('last_login_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admin_users', function (Blueprint $table): void {
            foreach (['password_changed_at', 'app_authentication_recovery_codes', 'app_authentication_secret'] as $column) {
                if (Schema::hasColumn('admin_users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
