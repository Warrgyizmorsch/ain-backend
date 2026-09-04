<?php

namespace App\Services;

use App\Models\EmailMessage;
use App\Models\EmailAttachment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Events\EmailReceived;

class EmailService
{
    public function __construct(private readonly EmailHtmlSanitizer $htmlSanitizer)
    {
    }

    /**
     * Send an Outbound Email and save to database.
     */
    public function sendEmail(array $data, array $uploadedFiles = []): EmailMessage
    {
        $toEmail = is_array($data['to']) ? implode(', ', $data['to']) : $data['to'];
        $subject = $data['subject'] ?? '(No Subject)';
        $bodyHtml = $data['body_html'] ?? $data['body'] ?? '';
        $bodyPlain = strip_tags($bodyHtml);

        // Resolve Email Account Configuration
        $account = null;
        if (!empty($data['account_id'])) {
            $account = \App\Models\EmailConfiguration::whereKey($data['account_id'])->where('is_active', true)->first();
        }
        if (!$account) {
            $account = \App\Models\EmailConfiguration::where('is_default', true)->where('is_active', true)->first()
                    ?: \App\Models\EmailConfiguration::where('is_active', true)->first();
        }

        if ($account) {
            $fromEmail = $account->email_address;
            $fromName = $account->from_name ?: $account->name;
        } else {
            $fromEmail = config('mail.from.address', env('MAIL_FROM_ADDRESS', 'noreply@ain-backend.com'));
            $fromName = config('mail.from.name', env('MAIL_FROM_NAME', 'Assignment In Need'));
        }

        if (!empty($data['account_id']) && !$account) {
            throw new \RuntimeException('The selected email account is inactive or does not exist.');
        }

        if ($account && (empty($account->host) || empty($account->username) || empty($account->password))) {
            throw new \RuntimeException('The selected SMTP account is incomplete.');
        }

        // Dynamically configure SMTP mailer for this account
        if ($account && !empty($account->host) && !empty($account->username) && !empty($account->password)) {
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $account->host,
                'mail.mailers.smtp.port' => (int) $account->port,
                'mail.mailers.smtp.encryption' => $account->encryption === 'none' ? null : ($account->encryption ?: 'tls'),
                'mail.mailers.smtp.username' => $account->username,
                'mail.mailers.smtp.password' => $account->password,
                'mail.from.address' => $fromEmail,
                'mail.from.name' => $fromName,
            ]);
            Mail::purge('smtp');
        }

        // Thread resolution
        $inReplyTo = $data['in_reply_to'] ?? null;
        $threadId = $data['thread_id'] ?? null;

        if (!$threadId && $inReplyTo) {
            $parentMsg = EmailMessage::where('message_id', $inReplyTo)->first();
            if ($parentMsg) {
                $threadId = $parentMsg->thread_id;
            }
        }

        if (!$threadId) {
            // Find existing thread with matching subject or email
            $cleanSubject = preg_replace('/^(Re:\s*|Fwd:\s*)+/i', '', trim($subject));
            $existingMsg = EmailMessage::where(function ($q) use ($toEmail) {
                $q->where('from_email', $toEmail)->orWhere('to_email', 'like', "%{$toEmail}%");
            })
            ->where(function ($q) use ($cleanSubject) {
                $q->where('subject', $cleanSubject)
                  ->orWhere('subject', 'like', "%{$cleanSubject}%");
            })
            ->when($account, fn ($q) => $q->where('email_configuration_id', $account->id))
            ->orderByDesc('id')
            ->first();

            $threadId = $existingMsg ? $existingMsg->thread_id : (string) Str::uuid();
        }

        $messageId = '<' . Str::random(24) . '.' . time() . '@' . (request()->getHost() ?? 'ain-backend.com') . '>';

        // If updating an existing draft
        if (!empty($data['draft_id'])) {
            $emailMsg = EmailMessage::find($data['draft_id']);
            if ($emailMsg) {
                $emailMsg->update([
                    'message_id' => $messageId,
                    'email_configuration_id' => $account?->id,
                    'in_reply_to' => $inReplyTo,
                    'thread_id' => $threadId,
                    'from_email' => $fromEmail,
                    'from_name' => $fromName,
                    'to_email' => $toEmail,
                    'to_name' => $data['to_name'] ?? null,
                    'cc' => $data['cc'] ?? null,
                    'bcc' => $data['bcc'] ?? null,
                    'subject' => $subject,
                    'body_html' => $bodyHtml,
                    'body_plain' => $bodyPlain,
                    'folder' => 'sent',
                    'direction' => 'outbound',
                    'status' => 'pending',
                    'is_read' => true,
                    'is_draft' => false,
                    'sent_at' => now(),
                ]);
            }
        }

        if (empty($emailMsg)) {
            $emailMsg = EmailMessage::create([
                'message_id' => $messageId,
                'email_configuration_id' => $account?->id,
                'in_reply_to' => $inReplyTo,
                'thread_id' => $threadId,
                'from_email' => $fromEmail,
                'from_name' => $fromName,
                'to_email' => $toEmail,
                'to_name' => $data['to_name'] ?? null,
                'cc' => $data['cc'] ?? null,
                'bcc' => $data['bcc'] ?? null,
                'subject' => $subject,
                'body_html' => $bodyHtml,
                'body_plain' => $bodyPlain,
                'folder' => 'sent',
                'direction' => 'outbound',
                'status' => 'pending',
                'is_read' => true,
                'is_draft' => false,
                'has_attachments' => !empty($uploadedFiles),
                'sent_at' => now(),
            ]);
        }

        // Handle file attachments
        $savedAttachments = [];
        if (!empty($uploadedFiles)) {
            foreach ($uploadedFiles as $file) {
                if ($file && $file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $mimeType = $file->getClientMimeType();
                    $fileSize = $file->getSize();
                    $path = $file->store('email_attachments', 'local');

                    $attachment = EmailAttachment::create([
                        'email_message_id' => $emailMsg->id,
                        'filename' => $originalName,
                        'file_path' => $path,
                        'mime_type' => $mimeType,
                        'file_size' => $fileSize,
                        'is_inline' => false,
                    ]);

                    $savedAttachments[] = [
                        'file' => $file,
                        'filename' => $originalName,
                        'mime' => $mimeType,
                        'path' => Storage::disk('local')->path($path),
                    ];
                }
            }
            $emailMsg->update(['has_attachments' => true]);
        }

        // Send via Laravel Mail / SMTP
        try {
            Mail::send([], [], function ($message) use ($toEmail, $fromEmail, $fromName, $subject, $bodyHtml, $data, $messageId, $inReplyTo, $savedAttachments) {
                $recipients = array_map('trim', explode(',', $toEmail));
                $message->to($recipients)
                        ->from($fromEmail, $fromName)
                        ->subject($subject)
                        ->html($bodyHtml);

                if (!empty($data['cc'])) {
                    $ccList = array_map('trim', explode(',', $data['cc']));
                    $message->cc($ccList);
                }

                if (!empty($data['bcc'])) {
                    $bccList = array_map('trim', explode(',', $data['bcc']));
                    $message->bcc($bccList);
                }

                // Custom Headers
                $headers = $message->getHeaders();
                $headers->addIdHeader('Message-ID', trim($messageId, '<>'));
                if ($inReplyTo) {
                    $headers->addTextHeader('In-Reply-To', $inReplyTo);
                    $headers->addTextHeader('References', $inReplyTo);
                }

                // Attach files
                foreach ($savedAttachments as $att) {
                    if (file_exists($att['path'])) {
                        $message->attach($att['path'], [
                            'as' => $att['filename'],
                            'mime' => $att['mime'],
                        ]);
                    }
                }
            });

            $emailMsg->update(['status' => 'sent']);
        } catch (\Exception $e) {
            Log::error('Email SMTP sending failed: '.$e->getMessage(), ['email_message_id' => $emailMsg->id]);
            $emailMsg->update(['status' => 'failed', 'sent_at' => null]);
            throw $e;
        }

        return $emailMsg;
    }

    /**
     * Save a Draft message.
     */
    public function saveDraft(array $data, array $uploadedFiles = []): EmailMessage
    {
        $toEmail = is_array($data['to'] ?? '') ? implode(', ', $data['to']) : ($data['to'] ?? '');
        $subject = $data['subject'] ?? '';
        $bodyHtml = $data['body_html'] ?? $data['body'] ?? '';
        $bodyPlain = strip_tags($bodyHtml);
        $account = !empty($data['account_id'])
            ? \App\Models\EmailConfiguration::whereKey($data['account_id'])->where('is_active', true)->first()
            : null;
        $fromEmail = $account?->email_address ?: config('mail.from.address', env('MAIL_FROM_ADDRESS', 'noreply@ain-backend.com'));
        $fromName = $account?->from_name ?: ($account?->name ?: config('mail.from.name', env('MAIL_FROM_NAME', 'Assignment In Need')));

        $threadId = $data['thread_id'] ?? (string) Str::uuid();

        if (!empty($data['draft_id'])) {
            $draft = EmailMessage::find($data['draft_id']);
            if ($draft) {
                $draft->update([
                    'email_configuration_id' => $account?->id,
                    'to_email' => $toEmail,
                    'subject' => $subject,
                    'body_html' => $bodyHtml,
                    'body_plain' => $bodyPlain,
                    'cc' => $data['cc'] ?? null,
                    'bcc' => $data['bcc'] ?? null,
                    'folder' => 'drafts',
                    'is_draft' => true,
                    'updated_at' => now(),
                ]);
                return $draft;
            }
        }

        $draft = EmailMessage::create([
            'email_configuration_id' => $account?->id,
            'thread_id' => $threadId,
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'to_email' => $toEmail,
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_plain' => $bodyPlain,
            'cc' => $data['cc'] ?? null,
            'bcc' => $data['bcc'] ?? null,
            'folder' => 'drafts',
            'direction' => 'outbound',
            'status' => 'draft',
            'is_read' => true,
            'is_draft' => true,
            'has_attachments' => !empty($uploadedFiles),
        ]);

        if (!empty($uploadedFiles)) {
            foreach ($uploadedFiles as $file) {
                if ($file && $file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $mimeType = $file->getClientMimeType();
                    $fileSize = $file->getSize();
                    $path = $file->store('email_attachments', 'local');

                    EmailAttachment::create([
                        'email_message_id' => $draft->id,
                        'filename' => $originalName,
                        'file_path' => $path,
                        'mime_type' => $mimeType,
                        'file_size' => $fileSize,
                        'is_inline' => false,
                    ]);
                }
            }
        }

        return $draft;
    }

    /**
     * Process Inbound Webhook (SendGrid, Mailgun, Postmark, Custom webhook).
     */
    public function processInboundWebhook(array $payload): ?EmailMessage
    {
        $from = $payload['from'] ?? $payload['sender'] ?? 'unknown@example.com';
        $fromEmail = $from;
        $fromName = '';
        if (preg_match('/(.*)<(.+)>/', $from, $matches)) {
            $fromName = trim($matches[1], ' "');
            $fromEmail = trim($matches[2]);
        }

        $toEmail = $payload['to'] ?? $payload['recipient'] ?? '';
        $account = \App\Models\EmailConfiguration::where('is_active', true)
            ->whereRaw('LOWER(email_address) = ?', [strtolower(trim((string) $toEmail))])
            ->first();
        if (!$account) {
            Log::warning('Inbound email rejected because recipient is not configured.', ['recipient' => $toEmail]);
            return null;
        }
        $subject = $payload['subject'] ?? '(No Subject)';
        $bodyHtml = $this->htmlSanitizer->sanitize($payload['html'] ?? $payload['body-html'] ?? $payload['stripped-html'] ?? '');
        $bodyPlain = $payload['text'] ?? $payload['body-plain'] ?? $payload['stripped-text'] ?? '';
        if (empty($bodyHtml) && !empty($bodyPlain)) {
            $bodyHtml = nl2br(e($bodyPlain));
        }

        $messageId = $payload['Message-Id'] ?? $payload['message-id'] ?? ('<' . Str::random(24) . '@webhook.local>');
        $inReplyTo = $payload['In-Reply-To'] ?? $payload['in-reply-to'] ?? null;

        // Check if message already exists
        if (EmailMessage::where('message_id', $messageId)->exists()) {
            return null;
        }

        // Thread matching
        $threadId = null;
        if ($inReplyTo) {
            $parent = EmailMessage::where('message_id', $inReplyTo)->first();
            if ($parent) {
                $threadId = $parent->thread_id;
            }
        }

        if (!$threadId) {
            $cleanSubject = preg_replace('/^(Re:\s*|Fwd:\s*)+/i', '', trim($subject));
            $existingMsg = EmailMessage::where(function ($q) use ($fromEmail) {
                $q->where('from_email', $fromEmail)->orWhere('to_email', 'like', "%{$fromEmail}%");
            })
            ->where(function ($q) use ($cleanSubject) {
                $q->where('subject', $cleanSubject)
                  ->orWhere('subject', 'like', "%{$cleanSubject}%");
            })
            ->where('email_configuration_id', $account->id)
            ->orderByDesc('id')
            ->first();

            $threadId = $existingMsg ? $existingMsg->thread_id : (string) Str::uuid();
        }

        $msg = EmailMessage::create([
            'email_configuration_id' => $account->id,
            'message_id' => $messageId,
            'in_reply_to' => $inReplyTo,
            'thread_id' => $threadId,
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'to_email' => $toEmail,
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_plain' => $bodyPlain,
            'folder' => 'inbox',
            'direction' => 'inbound',
            'status' => 'received',
            'is_read' => false,
            'is_draft' => false,
            'has_attachments' => !empty($payload['_attachments']),
            'received_at' => now(),
        ]);

        foreach ($this->flattenUploadedFiles($payload['_attachments'] ?? []) as $file) {
            if (!$file->isValid() || $file->getSize() > 20 * 1024 * 1024) continue;
            $path = $file->store('email_attachments', 'local');
            EmailAttachment::create([
                'email_message_id' => $msg->id,
                'filename' => basename($file->getClientOriginalName()),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'is_inline' => false,
            ]);
        }

        $this->broadcastReceived($msg);

        return $msg;
    }

    /**
     * Synchronize Incoming Emails via IMAP over Native SSL Socket.
    /**
     * Synchronize Incoming Emails via IMAP over Native SSL Socket.
     * Works with configured EmailConfiguration accounts.
     */
    public function syncImap(?\App\Models\EmailConfiguration $targetAccount = null): array
    {
        $accounts = $targetAccount ? collect([$targetAccount]) : \App\Models\EmailConfiguration::where('is_active', true)->get();

        if ($accounts->isEmpty()) {
            return [
                'status' => 'warning',
                'message' => 'No active email configurations found to sync.',
                'synced_count' => 0
            ];
        }

        $totalSynced = 0;
        $failures = [];

        foreach ($accounts as $account) {
            $host = $account->incoming_host ?: 'imap.gmail.com';
            $port = (int) ($account->incoming_port ?: 993);
            $username = $account->incoming_username ?: $account->email_address;
            $password = $account->incoming_password;

            if (empty($username) || empty($password)) {
                $failures[] = "{$account->name}: incoming credentials are incomplete";
                continue;
            }

            $syncLock = Cache::lock("email-imap-sync-{$account->id}", 55);
            if (!$syncLock->get()) {
                continue;
            }

            try {
                $scheme = $account->incoming_encryption === 'ssl' ? 'ssl://' : 'tcp://';
                $socket = @fsockopen($scheme . $host, $port, $errno, $errstr, 12);
                if (!$socket) {
                    $failures[] = "{$account->name}: {$errstr} ({$errno})";
                    continue;
                }

                stream_set_timeout($socket, 15);
                fgets($socket); // read greeting

                // 1. LOGIN
                fputs($socket, 'TAG1 LOGIN '.$this->quoteImap((string) $username).' '.$this->quoteImap((string) $password)."\r\n");
                $loginRes = '';
                while ($line = fgets($socket)) {
                    $loginRes .= $line;
                    if (str_starts_with($line, 'TAG1 ')) break;
                }

                if (!str_contains($loginRes, 'TAG1 OK')) {
                    $failures[] = "{$account->name}: IMAP authentication failed";
                    fclose($socket);
                    continue;
                }

                // 2. SELECT INBOX, then sync stable UIDs in bounded batches.
                fputs($socket, "TAG2 SELECT INBOX\r\n");
                while ($line = fgets($socket)) {
                    if (str_starts_with($line, 'TAG2 ')) break;
                }

                $settings = $account->settings ?: [];
                if (!array_key_exists('last_imap_uid', $settings)) {
                    // First connection establishes the mailbox baseline. Historical
                    // messages are intentionally not imported; only later UIDs are new.
                    fputs($socket, "TAG3 UID SEARCH ALL\r\n");
                    $initialUids = [];
                    while ($line = fgets($socket)) {
                        if (preg_match('/^\* SEARCH\s*(.*)$/i', trim($line), $matches)) {
                            $initialUids = array_values(array_filter(array_map('intval', preg_split('/\s+/', trim($matches[1])))));
                        }
                        if (str_starts_with($line, 'TAG3 ')) break;
                    }
                    $settings['last_imap_uid'] = empty($initialUids) ? 0 : max($initialUids);
                    $settings['imap_baselined_at'] = now()->toIso8601String();
                    $account->update(['settings' => $settings]);
                    fputs($socket, "TAG_OUT LOGOUT\r\n");
                    fclose($socket);
                    continue;
                }

                $lastUid = (int) ($settings['last_imap_uid'] ?? 0);
                $nextUid = $lastUid + 1;

                // Query ONLY strictly new messages with UID > lastUid (avoids loading historical emails)
                fputs($socket, "TAG3 UID SEARCH UID {$nextUid}:*\r\n");
                $uids = [];
                while ($line = fgets($socket)) {
                    if (preg_match('/^\* SEARCH\s*(.*)$/i', trim($line), $matches)) {
                        $rawUids = array_values(array_filter(array_map('intval', preg_split('/\s+/', trim($matches[1])))));
                        $uids = array_values(array_filter($rawUids, fn ($u) => $u > $lastUid));
                    }
                    if (str_starts_with($line, 'TAG3 ')) break;
                }

                $pendingUids = array_slice($uids, 0, 50);
                foreach ($pendingUids as $uid) {
                    fputs($socket, "TAG_F{$uid} UID FETCH {$uid} (RFC822)\r\n");
                    $fetchData = '';
                    while ($line = fgets($socket)) {
                        if (str_starts_with($line, "TAG_F{$uid} ")) break;
                        $fetchData .= $line;
                    }

                    $parsed = $this->parseRawEmail($fetchData);
                    if ($parsed && !empty($parsed['from_email'])) {
                        if (!EmailMessage::where('message_id', $parsed['message_id'])->exists()) {
                            $this->saveParsedEmail($parsed, $account);
                            $totalSynced++;
                        }
                    }
                    $lastUid = max($lastUid, $uid);
                }
                $settings['last_imap_uid'] = $lastUid;
                $account->update(['settings' => $settings]);

                // LOGOUT
                fputs($socket, "TAG_OUT LOGOUT\r\n");
                fclose($socket);
            } catch (\Exception $e) {
                Log::error("IMAP Sync error for {$username}: " . $e->getMessage());
                $failures[] = "{$account->name}: {$e->getMessage()}";
            } finally {
                $syncLock->release();
            }
        }

        return [
            'status' => count($failures) === $accounts->count() ? 'error' : (count($failures) ? 'warning' : 'success'),
            'message' => count($failures)
                ? "Synced {$totalSynced} email(s); ".count($failures).' account(s) failed.'
                : "Successfully synced {$totalSynced} new email(s).",
            'synced_count' => $totalSynced,
            'errors' => $failures,
        ];
    }

    public function testConnections(\App\Models\EmailConfiguration $account): array
    {
        $smtp = $this->openSocket($account->host, (int) $account->port, $account->encryption === 'ssl');
        $this->expectSmtp($smtp, [220]);
        fwrite($smtp, "EHLO localhost\r\n");
        $this->expectSmtp($smtp, [250]);
        if ($account->encryption === 'tls') {
            fwrite($smtp, "STARTTLS\r\n");
            $this->expectSmtp($smtp, [220]);
            if (!stream_socket_enable_crypto($smtp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new \RuntimeException('SMTP TLS negotiation failed.');
            }
            fwrite($smtp, "EHLO localhost\r\n");
            $this->expectSmtp($smtp, [250]);
        }
        fwrite($smtp, "AUTH LOGIN\r\n");
        $this->expectSmtp($smtp, [334]);
        fwrite($smtp, base64_encode((string) $account->username)."\r\n");
        $this->expectSmtp($smtp, [334]);
        fwrite($smtp, base64_encode((string) $account->password)."\r\n");
        $this->expectSmtp($smtp, [235]);
        fwrite($smtp, "QUIT\r\n");
        fclose($smtp);

        $imap = $this->openSocket($account->incoming_host, (int) $account->incoming_port, $account->incoming_encryption === 'ssl');
        fgets($imap);
        $user = $this->quoteImap($account->incoming_username ?: $account->email_address);
        $password = $this->quoteImap((string) $account->incoming_password);
        fwrite($imap, "T1 LOGIN {$user} {$password}\r\n");
        $response = '';
        while (($line = fgets($imap)) !== false) {
            $response .= $line;
            if (str_starts_with($line, 'T1 ')) break;
        }
        fclose($imap);
        if (!str_contains($response, 'T1 OK')) {
            throw new \RuntimeException('IMAP authentication failed.');
        }

        return ['smtp' => true, 'imap' => true];
    }

    private function openSocket(?string $host, int $port, bool $ssl)
    {
        if (!$host || !preg_match('/^[a-z0-9.-]+$/i', $host) || $port < 1 || $port > 65535) {
            throw new \RuntimeException('Invalid mail server host or port.');
        }
        $socket = @stream_socket_client(($ssl ? 'ssl://' : 'tcp://').$host.':'.$port, $errno, $error, 10);
        if (!$socket) throw new \RuntimeException("Connection failed: {$error} ({$errno})");
        stream_set_timeout($socket, 10);
        return $socket;
    }

    private function expectSmtp($socket, array $codes): string
    {
        $response = '';
        while (($line = fgets($socket)) !== false) {
            $response .= $line;
            if (strlen($line) < 4 || $line[3] !== '-') break;
        }
        $code = (int) substr($response, 0, 3);
        if (!in_array($code, $codes, true)) {
            throw new \RuntimeException('SMTP authentication failed (server code '.$code.').');
        }
        return $response;
    }

    private function quoteImap(string $value): string
    {
        return '"'.addcslashes($value, "\\\"").'"';
    }

    /**
     * Parse raw email text into structured data with full MIME & Quoted-Printable decoding.
     */
    public function parseRawEmail(string $raw): ?array
    {
        $headers = [];
        $fromEmail = '';
        $fromName = '';
        $toEmail = '';
        $subject = '(No Subject)';
        $messageId = '';
        $inReplyTo = null;
        $date = now();

        if (preg_match('/^From:\s*(.*)$/mi', $raw, $m)) {
            $fromRaw = trim($m[1]);
            if (preg_match('/(.*)<(.+)>/', $fromRaw, $fromMatches)) {
                $fromName = trim($fromMatches[1], ' "\'');
                $fromEmail = trim($fromMatches[2]);
            } else {
                $fromEmail = trim($fromRaw, ' <>');
            }
        }

        if (preg_match('/^To:\s*(.*)$/mi', $raw, $m)) {
            $toEmail = trim($m[1]);
        }

        if (preg_match('/^Subject:\s*(.*)$/mi', $raw, $m)) {
            $subject = trim($m[1]);
            if (function_exists('mb_decode_mimeheader')) {
                $subject = mb_decode_mimeheader($subject);
            }
        }

        if (preg_match('/^Message-ID:\s*(.*)$/mi', $raw, $m)) {
            $messageId = trim($m[1]);
        }

        if (preg_match('/^In-Reply-To:\s*(.*)$/mi', $raw, $m)) {
            $inReplyTo = trim($m[1]);
        }

        if (preg_match('/^Date:\s*(.*)$/mi', $raw, $m)) {
            try {
                $date = Carbon::parse(trim($m[1]));
            } catch (\Exception $e) {
                $date = now();
            }
        }

        if (empty($messageId)) {
            $messageId = '<' . md5($fromEmail . $subject . $date) . '@ain-backend.com>';
        }

        // Full MIME body extraction
        $rawCleaned = preg_replace('/TAG_F\d+\s+OK.*/i', '', $raw);
        $rawCleaned = preg_replace('/\s*FLAGS\s*\(\\\\Seen\)\s*\)?/i', '', $rawCleaned);

        $headerBody = preg_split('/\r?\n\r?\n/', $rawCleaned, 2);
        $headerText = $headerBody[0] ?? '';
        $bodyText = $headerBody[1] ?? '';

        $attachments = [];
        $mimeContent = $this->parseMimeEntity($rawCleaned, $attachments);
        $bodyHtml = $mimeContent['html'];
        $bodyPlain = $mimeContent['plain'];

        if (empty($bodyHtml) && empty($bodyPlain)) {
            $body = $bodyText ?: $rawCleaned;
            if (strpos($body, '=3D') !== false || preg_match('/=\r?\n/', $body)) {
                $body = quoted_printable_decode($body);
            }
            if (preg_match('/<[a-z][\s\S]*>/i', $body)) {
                $bodyHtml = $body;
                $bodyPlain = strip_tags($body);
            } else {
                $bodyPlain = $body;
                $bodyHtml = nl2br(e($body));
            }
        } elseif (!empty($bodyHtml) && empty($bodyPlain)) {
            $bodyPlain = strip_tags($bodyHtml);
        } elseif (!empty($bodyPlain) && empty($bodyHtml)) {
            $bodyHtml = nl2br(e($bodyPlain));
        }

        // Clean up stray boundary markers and protocol artifacts
        $bodyHtml = preg_replace('/--[0-9a-zA-Z_\-=.\/]{10,80}(--)?/i', '', $bodyHtml);
        $bodyPlain = preg_replace('/--[0-9a-zA-Z_\-=.\/]{10,80}(--)?/i', '', $bodyPlain);
        $bodyHtml = preg_replace('/Content-Type:\s*text\/(html|plain)[^\r\n]*/i', '', $bodyHtml);
        $bodyPlain = preg_replace('/Content-Type:\s*text\/(html|plain)[^\r\n]*/i', '', $bodyPlain);
        $bodyHtml = preg_replace('/Content-Transfer-Encoding:[^\r\n]*/i', '', $bodyHtml);
        $bodyPlain = preg_replace('/Content-Transfer-Encoding:[^\r\n]*/i', '', $bodyPlain);

        // Sanitize binary garbage / corrupt image bytes
        $bodyHtml = preg_replace('/4[\?M]4[\?M]4[\?M][\s\S]*?(?=\[image|\n\n|$)/u', '', $bodyHtml);
        $bodyPlain = preg_replace('/4[\?M]4[\?M]4[\?M][\s\S]*?(?=\[image|\n\n|$)/u', '', $bodyPlain);
        $bodyHtml = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $bodyHtml);
        $bodyPlain = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $bodyPlain);

        // Sanitize valid UTF-8
        $bodyHtml = mb_convert_encoding(trim($bodyHtml), 'UTF-8', 'UTF-8');
        $bodyPlain = mb_convert_encoding(trim($bodyPlain), 'UTF-8', 'UTF-8');

        return [
            'from_email' => $fromEmail,
            'from_name' => $fromName ?: $fromEmail,
            'to_email' => $toEmail,
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_plain' => $bodyPlain,
            'message_id' => $messageId,
            'in_reply_to' => $inReplyTo,
            'received_at' => $date,
            'attachments' => $attachments,
        ];
    }

    /**
     * Recursively extract text and attachments from nested multipart MIME entities.
     */
    private function parseMimeEntity(string $entity, array &$attachments): array
    {
        $split = preg_split('/\r?\n\r?\n/', $entity, 2);
        $headers = $split[0] ?? '';
        $body = $split[1] ?? '';

        if (preg_match('/Content-Type:\s*multipart\/[^;\r\n]+;[\s\S]*?boundary=["\']?([^"\'\r\n;]+)/i', $headers, $match)) {
            $html = '';
            $plain = '';
            $boundary = trim($match[1]);

            foreach (explode('--'.$boundary, $body) as $part) {
                $part = trim($part);
                if ($part === '' || $part === '--') {
                    continue;
                }

                $content = $this->parseMimeEntity($part, $attachments);
                if ($html === '' && $content['html'] !== '') {
                    $html = $content['html'];
                }
                if ($plain === '' && $content['plain'] !== '') {
                    $plain = $content['plain'];
                }
            }

            return ['html' => $html, 'plain' => $plain];
        }

        $encoding = '7bit';
        if (preg_match('/Content-Transfer-Encoding:\s*([^\r\n;]+)/i', $headers, $match)) {
            $encoding = strtolower(trim($match[1]));
        }
        if ($encoding === 'quoted-printable') {
            $body = quoted_printable_decode($body);
        } elseif ($encoding === 'base64') {
            $body = base64_decode(preg_replace('/\s+/', '', $body), true) ?: '';
        }

        $isAttachment = preg_match('/Content-Disposition:\s*attachment/i', $headers)
            || preg_match('/(?:filename|name)=["\']?([^"\'\r\n;]+)/i', $headers);
        if ($isAttachment) {
            if (preg_match('/(?:filename|name)=["\']?([^"\'\r\n;]+)/i', $headers, $filenameMatch)) {
                $filename = basename(trim($filenameMatch[1]));
                if ($filename !== '' && strlen($body) <= 20 * 1024 * 1024) {
                    preg_match('/Content-Type:\s*([^;\r\n]+)/i', $headers, $mimeMatch);
                    $attachments[] = [
                        'filename' => $filename,
                        'mime_type' => trim($mimeMatch[1] ?? 'application/octet-stream'),
                        'content' => $body,
                    ];
                }
            }

            return ['html' => '', 'plain' => ''];
        }

        if (preg_match('/Content-Type:\s*text\/html/i', $headers)) {
            return ['html' => $body, 'plain' => ''];
        }
        if (preg_match('/Content-Type:\s*text\/plain/i', $headers)) {
            return ['html' => '', 'plain' => $body];
        }

        return ['html' => '', 'plain' => ''];
    }

    /**
     * Save parsed email to database with proper threading.
     */
    public function saveParsedEmail(array $data, ?\App\Models\EmailConfiguration $account = null): EmailMessage
    {
        $fromEmail = $data['from_email'];
        $subject = $data['subject'] ?? '(No Subject)';
        $inReplyTo = $data['in_reply_to'] ?? null;

        $threadId = null;
        if ($inReplyTo) {
            $parent = EmailMessage::where('message_id', $inReplyTo)->first();
            if ($parent) {
                $threadId = $parent->thread_id;
            }
        }

        if (!$threadId) {
            $cleanSubject = preg_replace('/^(Re:\s*|Fwd:\s*)+/i', '', trim($subject));
            $existingMsg = EmailMessage::where(function ($q) use ($fromEmail) {
                $q->where('from_email', $fromEmail)->orWhere('to_email', 'like', "%{$fromEmail}%");
            })
            ->where(function ($q) use ($cleanSubject) {
                $q->where('subject', $cleanSubject)
                  ->orWhere('subject', 'like', "%{$cleanSubject}%");
            })
            ->when($account, fn ($q) => $q->where('email_configuration_id', $account->id))
            ->orderByDesc('id')
            ->first();

            $threadId = $existingMsg ? $existingMsg->thread_id : (string) Str::uuid();
        }

        $message = EmailMessage::create([
            'email_configuration_id' => $account?->id,
            'message_id' => $data['message_id'],
            'in_reply_to' => $inReplyTo,
            'thread_id' => $threadId,
            'from_email' => $fromEmail,
            'from_name' => $data['from_name'] ?? null,
            'to_email' => $data['to_email'] ?? '',
            'subject' => $subject,
            'body_html' => $this->htmlSanitizer->sanitize($data['body_html'] ?? ''),
            'body_plain' => $data['body_plain'] ?? '',
            'folder' => 'inbox',
            'direction' => 'inbound',
            'status' => 'received',
            'is_read' => false,
            'is_draft' => false,
            'has_attachments' => !empty($data['attachments']),
            'received_at' => $data['received_at'] ?? now(),
        ]);

        foreach ($data['attachments'] ?? [] as $attachment) {
            $path = 'email_attachments/'.Str::uuid().'-'.basename($attachment['filename']);
            Storage::disk('local')->put($path, $attachment['content']);
            EmailAttachment::create([
                'email_message_id' => $message->id,
                'filename' => basename($attachment['filename']),
                'file_path' => $path,
                'mime_type' => $attachment['mime_type'] ?? 'application/octet-stream',
                'file_size' => strlen($attachment['content']),
                'is_inline' => false,
            ]);
        }

        $this->broadcastReceived($message);

        return $message;
    }

    private function broadcastReceived(EmailMessage $message): void
    {
        try {
            event(new EmailReceived($message));
        } catch (\Throwable $e) {
            Log::warning('Email stored but realtime broadcast failed.', [
                'email_message_id' => $message->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function flattenUploadedFiles(array $files): array
    {
        $flat = [];
        array_walk_recursive($files, function ($file) use (&$flat) {
            if ($file instanceof \Illuminate\Http\UploadedFile) $flat[] = $file;
        });
        return $flat;
    }
}
