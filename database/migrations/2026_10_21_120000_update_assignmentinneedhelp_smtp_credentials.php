<?php

use App\Models\EmailConfiguration;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $account = EmailConfiguration::where('email_address', 'assignmentinneedhelp@gmail.com')->first();
        if ($account) {
            $account->password = 'wickkjpporzqnnuz';
            $account->incoming_password = 'wickkjpporzqnnuz';
            $account->host = 'smtp.gmail.com';
            $account->port = 465;
            $account->encryption = 'ssl';
            $account->username = 'assignmentinneedhelp@gmail.com';
            $account->is_active = true;
            $account->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
