<?php

namespace Database\Seeders;

use App\Models\EmailConfiguration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class EmailConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds for WhatsApp-like Email Plugin accounts.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Assignment Help',
                'email_address' => 'assignmentinneedhelp@gmail.com',
                'from_name' => 'Assignment Help',
                'driver' => 'smtp',
                'host' => 'smtp.gmail.com',
                'port' => 465,
                'encryption' => 'ssl',
                'username' => 'assignmentinneedhelp@gmail.com',
                'password' => 'bguttdxpipzouwzm',
                'incoming_protocol' => 'imap',
                'incoming_host' => 'imap.gmail.com',
                'incoming_port' => 993,
                'incoming_encryption' => 'ssl',
                'incoming_username' => 'assignmentinneedhelp@gmail.com',
                'incoming_password' => 'bguttdxpipzouwzm',
                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Write Email',
                'email_address' => 'order@assignnmentinneed.com',
                'from_name' => 'Write Email',
                'driver' => 'smtp',
                'host' => 'smtp.gmail.com',
                'port' => 465,
                'encryption' => 'ssl',
                'username' => 'order@assignnmentinneed.com',
                'password' => 'bguttdxpipzouwzm',
                'incoming_protocol' => 'imap',
                'incoming_host' => 'imap.gmail.com',
                'incoming_port' => 993,
                'incoming_encryption' => 'ssl',
                'incoming_username' => 'order@assignnmentinneed.com',
                'incoming_password' => 'bguttdxpipzouwzm',
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        // Purge old configurations so only Assignment Help and Write Email exist
        EmailConfiguration::whereNotIn('email_address', [
            'assignmentinneedhelp@gmail.com',
            'order@assignnmentinneed.com'
        ])->delete();

        // If setting assignmentinneedhelp@gmail.com as default, ensure others aren't marked default
        EmailConfiguration::where('email_address', '!=', 'assignmentinneedhelp@gmail.com')
            ->update(['is_default' => false]);

        foreach ($accounts as $accountData) {
            $email = $accountData['email_address'];

            $config = EmailConfiguration::updateOrCreate(
                ['email_address' => $email],
                $accountData
            );

            $this->command?->info("Configured email account: {$email} ({$accountData['name']})");
        }

        // Synchronize Email navigation menus and user role permissions
        EmailConfiguration::syncEmailSubmenus();
        $this->command?->info("Synchronized Email plugin menus and permissions.");
    }
}
