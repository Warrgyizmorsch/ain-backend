<?php

namespace App\Console\Commands;

use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireWalletCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run with: php artisan wallet:expire
     *
     * Suggestion: Add to schedule() in App\Console\Kernel:
     * $schedule->command('wallet:expire')->daily();
     */
    protected $signature = 'wallet:expire';

    /**
     * The console command description.
     */
    protected $description = 'Expire wallet credits whose expiry date has passed';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();

        // NOTE: Make sure WalletTransaction has:
        // - expires_at (nullable datetime)
        // - expired_processed (boolean, default false)
        $credits = WalletTransaction::where('type', 'credit')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->where('expired_processed', false)
            ->get();

        if ($credits->isEmpty()) {
            $this->info('No wallet credits to expire.');
            return Command::SUCCESS;
        }

        $this->info('Found ' . $credits->count() . ' wallet credit(s) to expire.');

        foreach ($credits as $credit) {
            try {
                $user = $credit->user;

                // Agar user hi nahi mila to sirf mark processed & skip
                if (! $user) {
                    $credit->expired_processed = true;
                    $credit->save();

                    $this->warn("Skipped credit ID {$credit->id}: user not found.");
                    continue;
                }

                $currentBalance = $user->wallet ?? 0;

                // Safety: jitna credit expire ho raha, utna hi cut karo (zyaada nahi)
                $amountToExpire = min($credit->amount, $currentBalance);

                if ($amountToExpire <= 0) {
                    // Wallet me balance hi nahi bacha, phir bhi credit ko processed mark kar do
                    $credit->expired_processed = true;
                    $credit->save();

                    $this->warn("Credit ID {$credit->id} for user {$user->id} has no usable balance left. Marked as processed.");
                    continue;
                }

                $newBalance = $currentBalance - $amountToExpire;

                // 1) Debit transaction entry as "Expired"
                WalletTransaction::create([
                    'user_id'       => $user->id,
                    'amount'        => $amountToExpire,
                    'type'          => 'debit',
                    'description'   => 'Wallet amount expired',
                    'balance_after' => $newBalance,
                    // expires_at null, kyunki yeh debit hai
                ]);

                // 2) User wallet update
                $user->wallet = $newBalance;
                $user->save();

                // 3) Original credit ko processed mark karo
                $credit->expired_processed = true;
                $credit->save();

                $this->line("Expired £{$amountToExpire} from user ID {$user->id} (credit ID {$credit->id}). New balance: £{$newBalance}");
            } catch (\Throwable $e) {
                // Single record fail ho to bhi baaki credits process hote rahen
                $this->error("Error processing credit ID {$credit->id}: " . $e->getMessage());
                \Log::error('wallet:expire error', [
                    'credit_id' => $credit->id,
                    'exception' => $e,
                ]);
            }
        }

        $this->info('Wallet credits expiry job completed.');
        return Command::SUCCESS;
    }
}
