<?php

namespace App\Console\Commands;

use App\Jobs\ProcessEmailCampaignJob;
use App\Models\EmailCampaign;
use Illuminate\Console\Command;

class SendScheduledCampaignsCommand extends Command
{
    protected $signature = 'campaigns:send-scheduled';
    protected $description = 'Dispatches scheduled email campaigns that are due to be sent';

    public function handle(): int
    {
        $dueCampaigns = EmailCampaign::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($dueCampaigns->isEmpty()) {
            $this->info('No scheduled email campaigns due for execution.');
            return 0;
        }

        foreach ($dueCampaigns as $campaign) {
            $this->info("Dispatching scheduled campaign #{$campaign->id} ({$campaign->name})...");
            $campaign->status = 'processing';
            $campaign->started_at = now();
            $campaign->save();

            ProcessEmailCampaignJob::dispatch($campaign->id);
        }

        return 0;
    }
}
