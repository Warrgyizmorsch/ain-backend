<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('login_otp_notifications')) {
            Schema::table('login_otp_notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('login_otp_notifications', 'failed_attempts')) {
                    $table->unsignedInteger('failed_attempts')->default(0)->after('status');
                }

                if (!Schema::hasColumn('login_otp_notifications', 'last_failed_at')) {
                    $table->timestamp('last_failed_at')->nullable()->after('failed_attempts');
                }

                if (!Schema::hasColumn('login_otp_notifications', 'blocked_at')) {
                    $table->timestamp('blocked_at')->nullable()->after('last_failed_at');
                }
            });
        }

        if (!Schema::hasTable('login_otp_system_bans')) {
            Schema::create('login_otp_system_bans', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address', 45)->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->timestamp('banned_until')->nullable()->index();
                $table->boolean('is_manual')->default(false);
                $table->unsignedInteger('attempts_count')->default(0);
                $table->timestamp('last_attempt_at')->nullable();
                $table->string('reason')->nullable();
                $table->unsignedBigInteger('banned_by')->nullable();
                $table->timestamp('unbanned_at')->nullable();
                $table->unsignedBigInteger('unbanned_by')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('login_otp_system_bans');

        if (Schema::hasTable('login_otp_notifications')) {
            Schema::table('login_otp_notifications', function (Blueprint $table) {
                if (Schema::hasColumn('login_otp_notifications', 'blocked_at')) {
                    $table->dropColumn('blocked_at');
                }

                if (Schema::hasColumn('login_otp_notifications', 'last_failed_at')) {
                    $table->dropColumn('last_failed_at');
                }

                if (Schema::hasColumn('login_otp_notifications', 'failed_attempts')) {
                    $table->dropColumn('failed_attempts');
                }
            });
        }
    }
};
