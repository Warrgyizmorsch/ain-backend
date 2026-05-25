<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            // Primary key
            $table->bigIncrements('id'); // yahan bhi users.id wala pattern follow karein

            /**
             * IMPORTANT:
             * Agar users table me:
             *    $table->id(); ya $table->bigIncrements('id'); hai:
             *        -> unsignedBigInteger('user_id')
             *
             * Agar users table me:
             *    $table->increments('id'); hai:
             *        -> unsignedInteger('user_id')
             */

            // 👉 OPTION 1: (Laravel naya project, users me $table->id(); / bigIncrements())
            $table->unsignedBigInteger('user_id');

            // 👉 OPTION 2: (AGAR tumhare users migration me increments('id') hai to upar wali line hata ke yeh likhna)
            // $table->unsignedInteger('user_id');

            $table->decimal('amount', 10, 2);
            $table->enum('type', ['credit', 'debit']);
            $table->string('description')->nullable();
            $table->decimal('balance_after', 10, 2)->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('expired_processed')->default(false);
            $table->timestamps();

            // Foreign key
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
