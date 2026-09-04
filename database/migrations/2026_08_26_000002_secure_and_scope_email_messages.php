<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('email_configurations')->orderBy('id')->each(function ($row) {
            $updates = [];
            foreach (['password', 'incoming_password'] as $column) {
                if (empty($row->{$column})) {
                    continue;
                }
                try {
                    Crypt::decryptString($row->{$column});
                } catch (\Throwable $e) {
                    $updates[$column] = Crypt::encryptString($row->{$column});
                }
            }
            if ($updates) {
                DB::table('email_configurations')->where('id', $row->id)->update($updates);
            }
        });

        DB::table('email_messages')->whereNotNull('message_id')->orderBy('id')->get()
            ->groupBy('message_id')->each(function ($messages) {
                $messages->skip(1)->each(function ($message) {
                    DB::table('email_messages')->where('id', $message->id)->update([
                        'message_id' => $message->message_id.'.duplicate-'.$message->id,
                    ]);
                });
            });

        Schema::table('email_messages', function (Blueprint $table) {
            $table->foreignId('email_configuration_id')
                ->nullable()
                ->after('id')
                ->constrained('email_configurations')
                ->nullOnDelete();
            $table->unique('message_id');
        });

        DB::table('email_configurations')->orderBy('id')->each(function ($account) {
            DB::table('email_messages')->whereNull('email_configuration_id')
                ->where(function ($query) use ($account) {
                    $query->where('from_email', $account->email_address)
                        ->orWhere('to_email', $account->email_address);
                })->update(['email_configuration_id' => $account->id]);
        });
    }

    public function down(): void
    {
        Schema::table('email_messages', function (Blueprint $table) {
            $table->dropUnique(['message_id']);
            $table->dropConstrainedForeignId('email_configuration_id');
        });
    }
};
