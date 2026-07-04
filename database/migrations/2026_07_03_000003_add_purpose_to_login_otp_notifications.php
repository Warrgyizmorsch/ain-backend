<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('login_otp_notifications')) {
            return;
        }

        Schema::table('login_otp_notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('login_otp_notifications', 'purpose')) {
                $table->string('purpose')->default('user_admin_approval')->after('status')->index();
            }

            if (!Schema::hasColumn('login_otp_notifications', 'email_to')) {
                $table->string('email_to')->nullable()->after('purpose');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('login_otp_notifications')) {
            return;
        }

        Schema::table('login_otp_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('login_otp_notifications', 'email_to')) {
                $table->dropColumn('email_to');
            }

            if (Schema::hasColumn('login_otp_notifications', 'purpose')) {
                $table->dropColumn('purpose');
            }
        });
    }
};
