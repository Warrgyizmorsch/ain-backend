<?php

namespace App\Jobs;

use App\Models\EmailCampaign;
use App\Models\EmailCampaignRecipient;
use App\Models\EmailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProcessEmailCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600; // 1 hour max
    public int $tries = 1;

    public function __construct(public int $campaignId)
    {
    }

    public function handle(): void
    {
        $campaign = EmailCampaign::with('emailConfiguration')->find($this->campaignId);
        if (!$campaign) {
            Log::warning("Email campaign #{$this->campaignId} not found for processing.");
            return;
        }

        // Check if paused or cancelled
        if (in_array($campaign->status, ['paused', 'cancelled', 'completed'])) {
            Log::info("Campaign #{$campaign->id} is {$campaign->status}. Processing halted.");
            return;
        }

        $campaign->status = 'processing';
        if (empty($campaign->started_at)) {
            $campaign->started_at = now();
        }
        $campaign->save();

        // 1. Configure SMTP dynamically for this campaign's selected account
        $account = $campaign->emailConfiguration ?: EmailConfiguration::where('is_default', true)->first();
        if (!$account || empty($account->host) || empty($account->username) || empty($account->password)) {
            Log::error("Campaign #{$campaign->id} failed: No valid active SMTP configuration found.");
            $campaign->status = 'failed';
            $campaign->save();
            return;
        }

        $encryption = $account->encryption === 'none' ? null : ($account->encryption ?: ((int) $account->port === 465 ? 'ssl' : 'tls'));
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $account->host,
            'mail.mailers.smtp.port' => (int) $account->port,
            'mail.mailers.smtp.encryption' => $encryption,
            'mail.mailers.smtp.username' => $account->username,
            'mail.mailers.smtp.password' => $account->password,
            'mail.from.address' => $account->email_address ?: env('MAIL_FROM_ADDRESS'),
            'mail.from.name' => $account->from_name ?: ($account->name ?: env('MAIL_FROM_NAME', 'Assignment In Need')),
        ]);
        Mail::purge('smtp');
        Mail::purge();

        // Stream options for Windows/proxy SSL stability
        try {
            $transport = Mail::mailer('smtp')->getSymfonyTransport();
            if ($transport instanceof \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport) {
                $stream = $transport->getStream();
                if ($stream instanceof \Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream) {
                    $stream->setStreamOptions([
                        'ssl' => [
                            'allow_self_signed' => true,
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ],
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Could not set SMTP stream options in campaign job: " . $e->getMessage());
        }

        $fromAddress = config('mail.from.address');
        $fromName    = config('mail.from.name');

        // 2. Fetch pending recipients in batches
        $batchSize = 25;
        $delayMs = max(50, (int) (60000 / max(1, $campaign->throttle_rate ?? 30))); // delay based on throttle rate

        while (true) {
            // Re-check campaign state to support live pause
            $campaign->refresh();
            if ($campaign->status === 'paused' || $campaign->status === 'cancelled') {
                Log::info("Campaign #{$campaign->id} paused or cancelled during execution.");
                break;
            }

            $recipients = EmailCampaignRecipient::where('campaign_id', $campaign->id)
                ->where('status', 'pending')
                ->take($batchSize)
                ->get();

            if ($recipients->isEmpty()) {
                // All recipients processed!
                $campaign->status = 'completed';
                $campaign->completed_at = now();
                $campaign->save();
                Log::info("Campaign #{$campaign->id} completed successfully.");
                break;
            }

            foreach ($recipients as $recipient) {
                // Ensure tracking token
                if (empty($recipient->tracking_token)) {
                    $recipient->tracking_token = Str::random(40);
                    $recipient->save();
                }

                // Prepare personalized content
                $recipientName = $recipient->recipient_name ?: 'Customer';
                $personalizedSubject = str_ireplace(
                    ['{{name}}', '{{email}}'],
                    [$recipientName, $recipient->recipient_email],
                    $campaign->subject
                );

                $trackingPixel = '<img src="' . route('emails.campaigns.track.open', $recipient->tracking_token) . '" width="1" height="1" style="display:none !important;" alt="" />';

                $personalizedHtml = str_ireplace(
                    ['{{name}}', '{{email}}'],
                    [htmlspecialchars($recipientName), htmlspecialchars($recipient->recipient_email)],
                    $campaign->body_html
                );

                // Append open tracking pixel
                if (str_contains($personalizedHtml, '</body>')) {
                    $personalizedHtml = str_replace('</body>', $trackingPixel . '</body>', $personalizedHtml);
                } else {
                    $personalizedHtml .= $trackingPixel;
                }

                // Wrap external links for click tracking
                $personalizedHtml = preg_replace_callback('/<a\s+(?:[^>]*?\s+)?href="([^"]*)"([^>]*)>/i', function ($matches) use ($recipient) {
                    $originalUrl = $matches[1];
                    $extraAttrs  = $matches[2];
                    if (str_starts_with($originalUrl, 'http://') || str_starts_with($originalUrl, 'https://')) {
                        $trackUrl = route('emails.campaigns.track.click', [
                            'token' => $recipient->tracking_token,
                            'url'   => urlencode($originalUrl)
                        ]);
                        return '<a href="' . htmlspecialchars($trackUrl) . '"' . $extraAttrs . '>';
                    }
                    return $matches[0];
                }, $personalizedHtml);

                try {
                    Mail::mailer('smtp')->send([], [], function ($message) use ($recipient, $personalizedSubject, $fromAddress, $fromName, $personalizedHtml) {
                        // Automatically embed local campaign images via CID inline attachment so Gmail/Outlook render them without broken '?'
                        $finalHtml = preg_replace_callback('/<img\s+([^>]*?)src="([^"]+)"([^>]*?)>/i', function ($matches) use ($message) {
                            $prefix = $matches[1];
                            $src    = $matches[2];
                            $suffix = $matches[3];

                            // Skip open tracking pixel
                            if (str_contains($src, '/email/track/open/')) {
                                return $matches[0];
                            }

                            // Match local uploads in uploads/ (campaigns or other assets)
                            if (str_contains($src, 'uploads/')) {
                                $pos = strpos($src, 'uploads/');
                                $relPath = substr($src, $pos);
                                $relPath = explode('?', $relPath)[0];
                                $localPath = public_path($relPath);
                                if (file_exists($localPath)) {
                                    $cid = $message->embed($localPath);
                                    return '<img ' . $prefix . 'src="' . $cid . '"' . $suffix . '>';
                                }
                            }

                            return $matches[0];
                        }, $personalizedHtml);

                        $message->to($recipient->recipient_email, $recipient->recipient_name ?: '')
                                ->from($fromAddress, $fromName)
                                ->subject($personalizedSubject)
                                ->html($finalHtml);
                    });

                    $recipient->status = 'sent';
                    $recipient->sent_at = now();
                    $recipient->save();

                    $campaign->increment('sent_count');
                    $campaign->increment('delivered_count');
                } catch (\Throwable $e) {
                    Log::error("Campaign #{$campaign->id} failed for recipient {$recipient->recipient_email}: " . $e->getMessage());
                    $recipient->status = 'failed';
                    $recipient->error_message = substr($e->getMessage(), 0, 500);
                    $recipient->save();

                    $campaign->increment('failed_count');
                }

                // Throttling pause
                usleep($delayMs * 1000);
            }
        }
    }
}
