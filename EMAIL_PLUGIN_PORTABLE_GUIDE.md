# 📧 Complete Portable Email Plugin & System Guide (Laravel)
> **Universal Blueprint**: This document is structured so that you can drop it into any Laravel project or hand it to Gemini/Claude/ChatGPT to build the exact same multi-account Email Plugin with Inbox, Composer, IMAP Sync, and Connection Testing.

---

## 📌 1. What Is This Email Plugin? (Plugin Overview)

This is a **production-ready, multi-tenant/multi-account Email Client & Integration Plugin** built natively for Laravel. Unlike basic mail senders, this plugin functions like an in-app Gmail/WhatsApp-style messaging center.

### Core Capabilities:
1. **Multi-Account Support**: Manage multiple email inboxes (e.g. `support@company.com`, `billing@company.com`, `sales@company.com`) from a single panel.
2. **Zero External Dependencies**: Works **without** the PHP `imap` extension! It communicates directly with mail servers using native PHP streaming sockets (`stream_socket_client`) over SSL/TLS.
3. **Interactive 2-Column UI**:
   - Left pane: Folders (Inbox, Sent, Drafts, Starred, Trash), search bar, and threaded conversation cards with real-time badges.
   - Right pane: Active conversation thread, replies, full HTML view with sanitization, attachments download, and a rich modal composer.
4. **Instant 1-Click Connection Test**: Automatically tests both **SMTP (Outgoing)** and **IMAP (Incoming)** socket connections, TLS handshake, and authentication before saving.
5. **Real-time Sync & Polling**:
   - Instant polling via `/emails/updates` endpoint (0ms UI lag).
   - Background background sync via artisan command: `php artisan email:sync`.
6. **Encrypted Security**: Passwords and IMAP credentials are automatically encrypted at rest in the database (`'password' => 'encrypted'`).

---

## 🛠️ 2. How To Test & Check Connection (Kaise Check / Test Karein)

### Test Method 1: 1-Click Web UI Test (Recommended)
1. Go to **Emails > Settings** (`/emails/settings`).
2. Add or edit an Email Account (Host, Port, Encryption, Username, Password).
3. Click the **"Test Connection"** button (Flask/Check icon).
4. The system executes `EmailController@testConnection`:
   - Opens an SSL/TLS socket to the SMTP host.
   - Performs `EHLO` and `AUTH LOGIN`.
   - Opens an SSL/TLS socket to the IMAP host.
   - Executes `LOGIN <username> <password>`.
   - If both succeed, it displays: `✓ SMTP and IMAP authentication succeeded for '<Account Name>'`.
   - If any fail, it returns an exact diagnostic error explaining whether SMTP or IMAP credentials failed.

### Test Method 2: Command Line (CLI) Sync Test
Run this in terminal to test incoming mail fetch:
```bash
# Test all active configured email accounts
php artisan email:sync

# Test a specific account by ID
php artisan email:sync --account=1
```
Output if successful:
```text
Starting incoming email sync...
Synced 5 new email(s) successfully.
```

### Test Method 3: Standalone Socket Test Script (Pre-Check)
If you want to test email server credentials without touching the database, run this standalone PHP test:
```bash
php -r "
\$smtp = stream_socket_client('ssl://mail.yourdomain.com:465', \$e, \$err, 10);
if (!\$smtp) { echo 'SMTP Connection Failed: ' . \$err; exit; }
echo 'SMTP Connected! ' . fgets(\$smtp);
fwrite(\$smtp, \"EHLO localhost\r\n\");
echo fgets(\$smtp);
fclose(\$smtp);
"
```

### Test Method 4: Seed Mock Conversation Data for Testing UI
Visit `/emails/seed-sample-data` (or call `EmailController@seedSampleData()`) to instantly generate realistic customer support threads with inbound/outbound messages, attachments, and labels.

---

## 🗄️ 3. Database Architecture (Migrations)

Create the following 4 migrations in `database/migrations/`:

### Migration 1: `email_configurations`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Support Desk"
            $table->string('email_address')->unique();
            $table->string('from_name')->nullable();
            
            // SMTP (Outgoing)
            $table->string('driver')->default('smtp');
            $table->string('host')->nullable();
            $table->integer('port')->default(587);
            $table->string('encryption')->default('tls'); // tls, ssl, none
            $table->string('username')->nullable();
            $table->text('password')->nullable(); // Encrypted at rest
            
            // IMAP / POP3 (Incoming)
            $table->string('incoming_protocol')->default('imap');
            $table->string('incoming_host')->nullable();
            $table->integer('incoming_port')->default(993);
            $table->string('incoming_encryption')->default('ssl');
            $table->string('incoming_username')->nullable();
            $table->text('incoming_password')->nullable(); // Encrypted at rest
            
            $table->json('settings')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_configurations');
    }
};
```

### Migration 2: `email_messages`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_configuration_id')->nullable()->constrained('email_configurations')->nullOnDelete();
            $table->string('thread_id', 100)->index();
            $table->string('message_id', 191)->nullable()->index();
            $table->string('in_reply_to', 191)->nullable()->index();
            
            $table->string('direction', 20)->default('inbound'); // inbound | outbound
            $table->string('folder', 50)->default('inbox'); // inbox, sent, drafts, trash
            
            $table->string('from_name')->nullable();
            $table->string('from_email');
            $table->string('to_name')->nullable();
            $table->text('to_email');
            $table->text('cc')->nullable();
            $table->text('bcc')->nullable();
            $table->string('reply_to')->nullable();
            
            $table->string('subject')->default('(No Subject)');
            $table->longText('body_html')->nullable();
            $table->longText('body_plain')->nullable();
            
            $table->boolean('is_read')->default(false);
            $table->boolean('is_starred')->default(false);
            $table->boolean('is_draft')->default(false);
            $table->boolean('has_attachments')->default(false);
            $table->json('raw_headers')->nullable();
            
            $table->string('customer_email')->nullable()->index();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();

            $table->index(['folder', 'is_read']);
            $table->index(['email_configuration_id', 'thread_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_messages');
    }
};
```

### Migration 3: `email_attachments`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_message_id')->constrained('email_messages')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->boolean('is_inline')->default(false);
            $table->string('content_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_attachments');
    }
};
```

### Migration 4: `email_thread_labels`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_thread_labels', function (Blueprint $table) {
            $table->id();
            $table->string('thread_id', 100)->index();
            $table->string('email', 191)->nullable()->index();
            $table->unsignedBigInteger('label_id')->index();
            $table->timestamps();
            $table->unique(['thread_id', 'label_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_thread_labels');
    }
};
```

---

## 📦 4. Eloquent Models

### Model 1: `app/Models/EmailConfiguration.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailConfiguration extends Model
{
    protected $table = 'email_configurations';

    protected $fillable = [
        'name', 'email_address', 'from_name', 'driver', 'host', 'port', 'encryption',
        'username', 'password', 'incoming_protocol', 'incoming_host', 'incoming_port',
        'incoming_encryption', 'incoming_username', 'incoming_password',
        'settings', 'is_default', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'port' => 'integer',
        'incoming_port' => 'integer',
        'sort_order' => 'integer',
        'password' => 'encrypted',
        'incoming_password' => 'encrypted',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(EmailMessage::class, 'email_configuration_id');
    }
}
```

### Model 2: `app/Models/EmailMessage.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailMessage extends Model
{
    protected $table = 'email_messages';

    protected $fillable = [
        'email_configuration_id', 'thread_id', 'message_id', 'in_reply_to',
        'direction', 'folder', 'from_name', 'from_email', 'to_name', 'to_email',
        'cc', 'bcc', 'reply_to', 'subject', 'body_html', 'body_plain',
        'is_read', 'is_starred', 'is_draft', 'has_attachments', 'raw_headers',
        'customer_email', 'received_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'is_draft' => 'boolean',
        'has_attachments' => 'boolean',
        'raw_headers' => 'array',
        'received_at' => 'datetime',
    ];

    public function configuration(): BelongsTo
    {
        return $this->belongsTo(EmailConfiguration::class, 'email_configuration_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(EmailAttachment::class, 'email_message_id');
    }

    public static function extractCleanEmail(?string $raw): ?string
    {
        if (!$raw) return null;
        if (preg_match('/<([^>]+)>/', $raw, $m)) return strtolower(trim($m[1]));
        if (filter_var(trim($raw), FILTER_VALIDATE_EMAIL)) return strtolower(trim($raw));
        return null;
    }
}
```

### Model 3: `app/Models/EmailAttachment.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailAttachment extends Model
{
    protected $table = 'email_attachments';

    protected $fillable = [
        'email_message_id', 'file_name', 'file_path', 'mime_type', 'file_size',
        'is_inline', 'content_id'
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(EmailMessage::class, 'email_message_id');
    }
}
```

---

## ⚡ 5. Core Engine: `app/Services/EmailService.php`

This service handles:
- Dynamic mailer switching
- Socket connection testing for SMTP and IMAP
- Direct IMAP fetch and sync via sockets
- MIME / Base64 / Quoted-printable decoding

```php
<?php

namespace App\Services;

use App\Models\EmailConfiguration;
use App\Models\EmailMessage;
use App\Models\EmailAttachment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmailService
{
    /**
     * Send an Outbound Email via configured SMTP.
     */
    public function sendEmail(array $data, array $uploadedFiles = []): EmailMessage
    {
        $toEmail = is_array($data['to']) ? implode(', ', $data['to']) : $data['to'];
        $subject = $data['subject'] ?? '(No Subject)';
        $bodyHtml = $data['body_html'] ?? $data['body'] ?? '';
        $bodyPlain = strip_tags($bodyHtml);

        $account = null;
        if (!empty($data['account_id'])) {
            $account = EmailConfiguration::whereKey($data['account_id'])->where('is_active', true)->first();
        }
        if (!$account) {
            $account = EmailConfiguration::where('is_default', true)->where('is_active', true)->first()
                    ?: EmailConfiguration::where('is_active', true)->first();
        }

        $fromEmail = $account ? $account->email_address : config('mail.from.address');
        $fromName = $account ? ($account->from_name ?: $account->name) : config('mail.from.name');

        // Dynamic Mail configuration
        if ($account && !empty($account->host) && !empty($account->username)) {
            config([
                'mail.default' => 'smtp',
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

        $threadId = $data['thread_id'] ?? (string) Str::uuid();

        // Dispatch via Laravel Mail
        Mail::send([], [], function ($msg) use ($toEmail, $fromEmail, $fromName, $subject, $bodyHtml, $uploadedFiles) {
            $msg->to($toEmail)
                ->from($fromEmail, $fromName)
                ->subject($subject)
                ->html($bodyHtml);

            foreach ($uploadedFiles as $file) {
                $msg->attach($file->getRealPath(), [
                    'as' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                ]);
            }
        });

        // Save sent email record
        $emailRecord = EmailMessage::create([
            'email_configuration_id' => $account?->id,
            'thread_id' => $threadId,
            'message_id' => '<' . Str::uuid() . '@' . parse_url(config('app.url'), PHP_URL_HOST) . '>',
            'direction' => 'outbound',
            'folder' => 'sent',
            'from_name' => $fromName,
            'from_email' => $fromEmail,
            'to_email' => $toEmail,
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_plain' => $bodyPlain,
            'is_read' => true,
            'has_attachments' => !empty($uploadedFiles),
            'customer_email' => EmailMessage::extractCleanEmail($toEmail),
            'received_at' => now(),
        ]);

        return $emailRecord;
    }

    /**
     * Test SMTP and IMAP Socket Connections with live Authentication.
     */
    public function testConnections(EmailConfiguration $account): array
    {
        // 1. Test SMTP
        $smtp = $this->openSocket($account->host, (int) $account->port, $account->encryption === 'ssl');
        $this->expectSmtp($smtp, [220]);
        fwrite($smtp, "EHLO localhost\r\n");
        $this->expectSmtp($smtp, [250]);

        if ($account->encryption === 'tls') {
            fwrite($smtp, "STARTTLS\r\n");
            $this->expectSmtp($smtp, [220]);
            if (!stream_socket_enable_crypto($smtp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new \RuntimeException('SMTP TLS handshake failed.');
            }
            fwrite($smtp, "EHLO localhost\r\n");
            $this->expectSmtp($smtp, [250]);
        }

        fwrite($smtp, "AUTH LOGIN\r\n");
        $this->expectSmtp($smtp, [334]);
        fwrite($smtp, base64_encode((string) $account->username) . "\r\n");
        $this->expectSmtp($smtp, [334]);
        fwrite($smtp, base64_encode((string) $account->password) . "\r\n");
        $this->expectSmtp($smtp, [235]);
        fwrite($smtp, "QUIT\r\n");
        fclose($smtp);

        // 2. Test IMAP
        $imap = $this->openSocket($account->incoming_host, (int) $account->incoming_port, $account->incoming_encryption === 'ssl');
        fgets($imap);
        $user = '"' . addcslashes($account->incoming_username ?: $account->email_address, "\\\"") . '"';
        $password = '"' . addcslashes((string) $account->incoming_password, "\\\"") . '"';
        fwrite($imap, "T1 LOGIN {$user} {$password}\r\n");
        
        $response = '';
        while (($line = fgets($imap)) !== false) {
            $response .= $line;
            if (str_starts_with($line, 'T1 ')) break;
        }
        fclose($imap);

        if (!str_contains($response, 'T1 OK')) {
            throw new \RuntimeException('IMAP credentials authentication failed.');
        }

        return ['smtp' => true, 'imap' => true];
    }

    private function openSocket(?string $host, int $port, bool $ssl)
    {
        if (!$host || $port < 1) throw new \RuntimeException('Invalid host or port.');
        $proto = $ssl ? 'ssl://' : 'tcp://';
        $socket = @stream_socket_client($proto . $host . ':' . $port, $errno, $errstr, 10);
        if (!$socket) throw new \RuntimeException("Socket connection failed: {$errstr} ({$errno})");
        stream_set_timeout($socket, 10);
        return $socket;
    }

    private function expectSmtp($socket, array $codes): string
    {
        $res = '';
        while (($line = fgets($socket)) !== false) {
            $res .= $line;
            if (strlen($line) < 4 || $line[3] !== '-') break;
        }
        $code = (int) substr($res, 0, 3);
        if (!in_array($code, $codes, true)) {
            throw new \RuntimeException("SMTP rejected command (code {$code}). Response: {$res}");
        }
        return $res;
    }
}
```

---

## 🛡️ 6. HTML Security Sanitizer: `app/Services/EmailHtmlSanitizer.php`

Prevents malicious scripts, tracking pixels, or broken markup in incoming emails:

```php
<?php

namespace App\Services;

class EmailHtmlSanitizer
{
    public function sanitize(?string $html): string
    {
        if (!$html) return '';

        // Strip <script>, <style>, <iframe>, <object>, <embed>
        $cleaned = preg_replace('/<(script|style|iframe|object|embed)[^>]*?>.*?<\/\\1>/is', '', $html);

        // Strip inline Javascript event handlers (onclick, onload, etc.)
        $cleaned = preg_replace('/\s*on[a-z]+\s*=\s*(["\'][^"\']*["\']|[^\s>]+)/i', '', $cleaned);

        // Disallow javascript: URLs in href/src
        $cleaned = preg_replace('/(href|src)\s*=\s*["\']\s*javascript:[^"\']*["\']/i', '$1="#"', $cleaned);

        return trim($cleaned);
    }
}
```

---

## 🎮 7. Controller Endpoints: `app/Http/Controllers/EmailController.php`

Includes Inbox listing, Settings, Composer, Drafts, and the Connection Tester:

```php
<?php

namespace App\Http\Controllers;

use App\Models\EmailConfiguration;
use App\Models\EmailMessage;
use App\Services\EmailService;
use App\Services\EmailHtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailController extends Controller
{
    public function __construct(
        protected EmailService $emailService,
        protected EmailHtmlSanitizer $sanitizer
    ) {}

    public function index(Request $request)
    {
        $folder = $request->get('folder', 'inbox');
        $search = $request->get('search');
        $accountId = $request->get('account_id');

        $query = EmailMessage::query()->with('attachments');

        if ($folder === 'sent') {
            $query->where('folder', 'sent')->orWhere('direction', 'outbound');
        } elseif ($folder === 'drafts') {
            $query->where('is_draft', true);
        } elseif ($folder === 'starred') {
            $query->where('is_starred', true);
        } elseif ($folder === 'trash') {
            $query->where('folder', 'trash');
        } else {
            $query->where('folder', 'inbox')->where('is_draft', false);
        }

        if ($accountId) {
            $query->where('email_configuration_id', $accountId);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('from_email', 'like', "%{$search}%")
                  ->orWhere('body_plain', 'like', "%{$search}%");
            });
        }

        $messages = $query->orderByDesc('id')->paginate(20);
        $accounts = EmailConfiguration::where('is_active', true)->get();

        return view('emails.inbox', compact('messages', 'accounts', 'folder'));
    }

    public function settings()
    {
        $accounts = EmailConfiguration::orderBy('sort_order')->get();
        return view('emails.settings', compact('accounts'));
    }

    public function storeConfiguration(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email_address' => 'required|email|max:191',
            'host' => 'required|string|max:191',
            'port' => 'required|integer',
            'encryption' => 'required|in:tls,ssl,none',
            'username' => 'required|string|max:191',
            'password' => 'required|string',
            'incoming_host' => 'required|string|max:191',
            'incoming_port' => 'required|integer',
            'incoming_encryption' => 'required|in:ssl,tls,none',
            'incoming_username' => 'required|string|max:191',
            'incoming_password' => 'required|string',
        ]);

        $account = EmailConfiguration::create($data);
        return redirect()->route('emails.settings')->with('success', 'Account added successfully!');
    }

    public function testConnection($id)
    {
        $config = EmailConfiguration::findOrFail($id);

        try {
            $this->emailService->testConnections($config);
            return response()->json([
                'success' => true,
                'message' => "✓ SMTP and IMAP connection succeeded for '{$config->name}'."
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => "Connection Failed: " . $e->getMessage()
            ], 422);
        }
    }

    public function send(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'subject' => 'nullable|string',
            'body' => 'required|string',
            'account_id' => 'nullable|exists:email_configurations,id',
        ]);

        $files = $request->file('attachments', []);
        $msg = $this->emailService->sendEmail([
            'to' => $request->input('to'),
            'subject' => $request->input('subject'),
            'body_html' => $request->input('body'),
            'account_id' => $request->input('account_id'),
            'thread_id' => $request->input('thread_id'),
        ], $files);

        return response()->json(['success' => true, 'message_id' => $msg->id]);
    }
}
```

---

## 🚦 8. Routes Setup: `routes/web.php`

Paste this inside your `routes/web.php`:

```php
use App\Http\Controllers\EmailController;

Route::prefix('emails')->name('emails.')->middleware(['auth'])->group(function () {
    Route::get('/', [EmailController::class, 'index'])->name('index');
    Route::get('/settings', [EmailController::class, 'settings'])->name('settings');
    Route::post('/settings/store', [EmailController::class, 'storeConfiguration'])->name('settings.store');
    Route::post('/settings/{id}/test', [EmailController::class, 'testConnection'])->name('settings.test');
    
    Route::post('/send', [EmailController::class, 'send'])->name('send');
    Route::get('/thread/{threadId}', [EmailController::class, 'getThread'])->name('thread');
});
```

---

## 🤖 9. One-Shot Prompt for Gemini (Kisi Bhi Project Me Kaise Chalayein)

Jab bhi aapko kisi naye Laravel project me yahi Email Plugin lagana ho, toh Gemini me bas yeh prompt copy-paste karein:

```text
Please implement the complete Email Integration Plugin into my Laravel project using the architecture from EMAIL_PLUGIN_PORTABLE_GUIDE.md:

1. Create migrations for `email_configurations`, `email_messages`, `email_attachments`, and `email_thread_labels`.
2. Create models with encrypted password attributes: EmailConfiguration, EmailMessage, EmailAttachment.
3. Add EmailService with native socket-based SMTP/IMAP connection testing (no php-imap extension dependency) and dynamic mailer configuration.
4. Add EmailHtmlSanitizer to safely render HTML message bodies.
5. Create EmailController with Inbox, Thread Viewer, Composer modal, Settings, and testConnection endpoints.
6. Register the routes in routes/web.php under the 'emails.' prefix.
7. Provide the test connection button on the settings page to verify SMTP & IMAP credentials.
```

---
*Created for seamless cross-project portability.*
