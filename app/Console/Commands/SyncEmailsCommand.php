<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailService;
use App\Models\EmailConfiguration;

class SyncEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sync {--account= : Sync only this email configuration ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync incoming emails via IMAP/SSL socket into the database';

    /**
     * Execute the console command.
     */
    public function handle(EmailService $emailService): int
    {
        $this->info('Starting incoming email sync...');
        $account = null;
        if ($this->option('account')) {
            $account = EmailConfiguration::whereKey((int) $this->option('account'))
                ->where('is_active', true)
                ->first();
            if (!$account) {
                $this->error('Active email account not found.');
                return Command::FAILURE;
            }
        }

        $result = $emailService->syncImap($account);

        if ($result['status'] === 'success') {
            $this->info($result['message']);
            return Command::SUCCESS;
        } elseif ($result['status'] === 'warning') {
            $this->warn($result['message']);
            return Command::SUCCESS;
        } else {
            $this->error('Sync Error: ' . $result['message']);
            foreach ($result['errors'] ?? [] as $error) {
                $this->line(' - '.$error);
            }
            return Command::FAILURE;
        }
    }
}
