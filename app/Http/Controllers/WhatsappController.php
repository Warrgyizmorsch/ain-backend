<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\WhatsappChatContactLabel;
use App\Models\WhatsappChatLabel;
use App\Models\WhatsappChatPanelSetting;
use App\Models\WhatsappMessage;
use App\Models\WhatsappSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class WhatsappController extends Controller
{
    private array $providers = ['ai-sense', 'wati', 'twilio', 'interakt'];

    public function settings(): View
    {
        $settings = WhatsappSetting::query()->get()->keyBy('provider');
        $activeSetting = $settings->firstWhere('is_active', true);
        $activeProvider = old('provider', $activeSetting?->provider ?? 'twilio');
        $providerSettings = $settings->mapWithKeys(function (WhatsappSetting $setting) {
            return [$setting->provider => $setting->settings ?? []];
        })->toArray();

        return view('back-end.whatsapp.settings', [
            'activeProvider' => $activeProvider,
            'providerSettings' => $providerSettings,
            'webhookUrl' => url('/api/webhooks/whatsapp'),
        ]);
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'provider' => ['required', 'in:' . implode(',', $this->providers)],
            'settings' => ['nullable', 'array'],
        ]);

        $provider = $validated['provider'];
        $settings = $request->input("settings.{$provider}", []);

        WhatsappSetting::query()->update(['is_active' => false]);

        WhatsappSetting::query()->updateOrCreate(
            ['provider' => $provider],
            [
                'settings' => $this->cleanSettings($settings),
                'is_active' => true,
                'updated_by' => Auth::id(),
                'created_by' => Auth::id(),
            ]
        );

        return back()->with('success', 'WhatsApp settings saved successfully.');
    }

    public function chat(Request $request): View
    {
        $activePhone = $request->query('phone');
        $contacts = $this->getContacts($activePhone);
        $selectedContact = collect($contacts)->firstWhere('active', true);
        $selectedPhone = $selectedContact['phone'] ?? null;

        if ($selectedPhone) {
            $this->markPhoneMessagesRead($selectedPhone);
            $contacts = $this->getContacts($selectedPhone);
            $selectedContact = collect($contacts)->firstWhere('active', true) ?? $selectedContact;
        }

        $panelDefinitions = $this->chatPanelDefinitions();
        $enabledPanelKeys = $this->enabledPanelKeys(Auth::id(), array_keys($panelDefinitions));
        $selectedPanel = $request->query('panel');
        $selectedPanel = in_array($selectedPanel, $enabledPanelKeys, true) ? $selectedPanel : null;
        $panelRows = $selectedPanel ? $this->panelRows($selectedPanel) : collect();
        $labels = WhatsappChatLabel::query()->orderBy('name')->get();
        $selectedContactLabels = $selectedPhone
            ? WhatsappChatContactLabel::query()->where('phone', $selectedPhone)->pluck('label_id')->all()
            : [];

        // Load all contact-label assignments for sidebar display
        $allPhones = collect($contacts)->pluck('phone')->filter()->values()->all();
        $allContactLabelMap = WhatsappChatContactLabel::query()
            ->whereIn('phone', $allPhones)
            ->get()
            ->groupBy('phone')
            ->map(fn($rows) => $rows->pluck('label_id')->all());

        $messages = $selectedPhone
            ? WhatsappMessage::query()
                ->where('phone', $selectedPhone)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get()
            : collect();

        return view('back-end.whatsapp.chat', [
            'dynamicContacts' => $contacts,
            'selectedContact' => $selectedContact,
            'selectedPhone' => $selectedPhone,
            'messages' => $messages,
            'panelDefinitions' => $panelDefinitions,
            'enabledPanelKeys' => $enabledPanelKeys,
            'selectedPanel' => $selectedPanel,
            'panelRows' => $panelRows,
            'labels' => $labels,
            'selectedContactLabels' => $selectedContactLabels,
            'allContactLabelMap' => $allContactLabelMap,
        ]);
    }

    public function messages(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'after_id' => ['nullable', 'integer', 'min:0'],
        ]);

        $phone = $validated['phone'];
        $afterId = (int) ($validated['after_id'] ?? 0);

        $messages = WhatsappMessage::query()
            ->where('phone', $phone)
            ->when($afterId > 0, fn ($query) => $query->where('id', '>', $afterId))
            ->where(function ($query) {
                $query->whereRaw("TRIM(COALESCE(message, '')) != ''")
                    ->orWhereNotNull('media_url');
            })
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $this->markPhoneMessagesRead($phone);

        return response()->json([
            'messages' => $messages->map(fn (WhatsappMessage $message) => $this->messagePayload($message))->values(),
            'statuses' => $this->recentOutboundStatuses($phone),
            'contacts' => $this->getContacts($phone),
            'typing' => Cache::has($this->typingCacheKey($phone)),
        ]);
    }

    public function markRead(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $updated = $this->markPhoneMessagesRead($validated['phone']);

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'contacts' => $this->getContacts($validated['phone']),
        ]);
    }

    public function markUnread(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $updated = WhatsappMessage::query()
            ->where('phone', $validated['phone'])
            ->where('direction', 'inbound')
            ->latest('id')
            ->limit(1)
            ->update(['status' => 'unread']);

        if (! $updated) {
            $latest = WhatsappMessage::query()
                ->where('phone', $validated['phone'])
                ->latest('id')
                ->first();

            if ($latest) {
                $latest->update(['status' => 'unread']);
                $updated = 1;
            }
        }

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'contacts' => $this->getContacts($validated['phone']),
        ]);
    }

    public function saveChatPanelSettings(Request $request): RedirectResponse
    {
        $definitions = $this->chatPanelDefinitions();
        $enabled = collect($request->input('panels', []))
            ->filter()
            ->keys()
            ->intersect(array_keys($definitions))
            ->values()
            ->all();

        foreach ($definitions as $key => $definition) {
            WhatsappChatPanelSetting::query()->updateOrCreate(
                ['user_id' => Auth::id(), 'panel_key' => $key],
                ['is_enabled' => in_array($key, $enabled, true)]
            );
        }

        return back()->with('success', 'WhatsApp chat settings saved.');
    }

    public function storeChatLabel(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'color' => ['required', 'string', 'max:20'],
        ]);

        WhatsappChatLabel::query()->firstOrCreate(
            ['name' => trim($validated['name'])],
            [
                'color' => $validated['color'],
                'created_by' => Auth::id(),
            ]
        );

        return redirect()
            ->route('whatsapp.chat', array_filter(['phone' => $request->input('phone')]))
            ->with('success', 'WhatsApp label created.');
    }

    public function saveContactLabels(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'labels' => ['nullable', 'array'],
        ]);

        $phone = $validated['phone'];
        $labelIds = collect($request->input('labels', []))
            ->filter()
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        WhatsappChatContactLabel::query()->where('phone', $phone)->delete();

        foreach ($labelIds as $labelId) {
            WhatsappChatContactLabel::query()->create([
                'phone' => $phone,
                'label_id' => $labelId,
                'assigned_by' => Auth::id(),
            ]);
        }

        return redirect()->route('whatsapp.chat', ['phone' => $phone])->with('success', 'Chat labels saved.');
    }

    public function startChat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'country_code' => ['required', 'string', 'max:8'],
            'mobile' => ['required', 'string', 'max:20'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $phone = $this->normalizePhone($validated['country_code'], $validated['mobile']);

        if (! empty($validated['message'])) {
            $this->storeOutboundMessage($phone, $validated['message']);
        } else {
            WhatsappMessage::query()->firstOrCreate(
                ['phone' => $phone, 'message' => ''],
                ['name' => $phone, 'direction' => 'outbound', 'status' => 'draft']
            );
        }

        return redirect()->route('whatsapp.chat', ['phone' => $phone]);
    }

    public function sendMessage(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = $this->storeOutboundMessage($validated['phone'], $validated['message']);
        $this->sendViaActiveProvider($message);
        $message->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $this->messagePayload($message),
                'contacts' => $this->getContacts($validated['phone']),
            ]);
        }

        return redirect()->route('whatsapp.chat', ['phone' => $validated['phone']]);
    }

    private function getContacts(?string $activePhone): array
    {
        $latestMessages = DB::table('whatsapp_messages as latest')
            ->join(DB::raw('(SELECT phone, MAX(id) as max_id FROM whatsapp_messages GROUP BY phone) as grouped'), function ($join) {
                $join->on('latest.id', '=', 'grouped.max_id');
            })
            ->select('latest.phone', 'latest.name', 'latest.message', 'latest.media_type', 'latest.media_name', 'latest.created_at', 'latest.id')
            ->orderByDesc('latest.created_at')
            ->orderByDesc('latest.id')
            ->get();

        $phones = $latestMessages->pluck('phone')->filter()->values();
        $users = User::query()
            ->whereIn('mobile_no', $phones)
            ->get()
            ->keyBy('mobile_no');

        return $latestMessages->map(function ($contact, int $index) use ($users, $activePhone) {
            $user = $users->get($contact->phone);
            $name = $user?->name ?: ($contact->name ?: $contact->phone);
            $unreadCount = WhatsappMessage::query()
                ->where('phone', $contact->phone)
                ->where('direction', 'inbound')
                ->where(function ($query) {
                    $query->whereNull('status')->orWhere('status', '!=', 'read');
                })
                ->count();

            return [
                'id' => $contact->id,
                'phone' => $contact->phone,
                'name' => $name,
                'msg' => $this->contactPreview($contact),
                'time' => optional($contact->created_at ? \Carbon\Carbon::parse($contact->created_at) : null)->isToday()
                    ? \Carbon\Carbon::parse($contact->created_at)->format('H:i')
                    : optional($contact->created_at ? \Carbon\Carbon::parse($contact->created_at) : null)->format('D'),
                'active' => $contact->phone === $activePhone,
                'badge' => $unreadCount,
                'color' => $this->avatarColor($index),
                'status' => $unreadCount > 0 ? 'online' : 'offline',
            ];
        })->toArray();
    }

    private function contactPreview(object $contact): string
    {
        $text = trim((string) ($contact->message ?? ''));

        if ($text !== '') {
            return $text;
        }

        return match ($contact->media_type ?? null) {
            'image' => 'Image attachment',
            'video' => 'Video attachment',
            'audio' => 'Audio attachment',
            'document' => $contact->media_name ?: 'Document attachment',
            default => 'New chat started',
        };
    }

    private function markPhoneMessagesRead(string $phone): int
    {
        return WhatsappMessage::query()
            ->where('phone', $phone)
            ->where('direction', 'inbound')
            ->where(function ($query) {
                $query->whereNull('status')->orWhere('status', '!=', 'read');
            })
            ->update(['status' => 'read']);
    }

    private function messagePayload(WhatsappMessage $message): array
    {
        return [
            'id'         => $message->id,
            'phone'      => $message->phone,
            'name'       => $message->name,
            'message'    => $message->message,
            'direction'  => $message->direction,
            'status'     => $message->status,
            'time'       => optional($message->created_at)->format('H:i'),
            'created_at' => optional($message->created_at)->toDateTimeString(),
            'media_url'  => $message->media_url,
            'media_type' => $message->media_type,
            'media_name' => $message->media_name,
            'media_size' => $message->media_size,
        ];
    }

    private function recentOutboundStatuses(string $phone): array
    {
        return WhatsappMessage::query()
            ->where('phone', $phone)
            ->where('direction', 'outbound')
            ->latest('id')
            ->limit(100)
            ->get(['id', 'status'])
            ->map(fn (WhatsappMessage $message) => [
                'id' => $message->id,
                'status' => $message->status,
            ])
            ->values()
            ->all();
    }

    private function typingCacheKey(string $phone): string
    {
        return 'whatsapp_typing:' . preg_replace('/[^0-9+]/', '', $phone);
    }

    private function storeOutboundMessage(string $phone, string $text): WhatsappMessage
    {
        return WhatsappMessage::query()->create([
            'phone' => $phone,
            'name' => Auth::user()?->name ?? 'Admin',
            'message' => $text,
            'direction' => 'outbound',
            'status' => 'queued',
        ]);
    }

    private function sendViaActiveProvider(WhatsappMessage $message): void
    {
        $setting = WhatsappSetting::query()->where('is_active', true)->first();

        if (! $setting || $setting->provider !== 'twilio') {
            return;
        }

        $config = $setting->settings ?? [];
        $sid = $config['account_sid'] ?? null;
        $token = $config['auth_token'] ?? null;
        $from = $config['whatsapp_from_number'] ?? null;

        if (! $sid || ! $token || ! $from) {
            return;
        }

        try {
            $payload = [
                'From' => str_starts_with($from, 'whatsapp:') ? $from : 'whatsapp:' . $from,
                'To' => str_starts_with($message->phone, 'whatsapp:') ? $message->phone : 'whatsapp:' . $message->phone,
                'StatusCallback' => url('/api/webhooks/whatsapp'),
            ];

            if (trim((string) $message->message) !== '') {
                $payload['Body'] = $message->message;
            }

            if ($message->media_url) {
                $payload['MediaUrl'] = $this->providerMediaUrl($message->media_url);
            }

            if (! isset($payload['Body']) && ! isset($payload['MediaUrl'])) {
                return;
            }

            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", $payload);

            if ($response->successful()) {
                $message->update([
                    'wa_message_id' => $response->json('sid'),
                    'status' => $response->json('status', 'sent'),
                ]);
            } else {
                $message->update(['status' => 'failed']);
                Log::warning('Twilio WhatsApp send failed', ['response' => $response->json()]);
            }
        } catch (\Throwable $exception) {
            $message->update(['status' => 'failed']);
            Log::error('Twilio WhatsApp send exception', ['exception' => $exception]);
        }
    }

    private function cleanSettings(array $settings): array
    {
        return collect($settings)
            ->map(fn ($value) => is_string($value) ? trim($value) : $value)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->toArray();
    }

    private function normalizePhone(string $countryCode, string $mobile): string
    {
        return '+' . ltrim(preg_replace('/\D+/', '', $countryCode . $mobile), '+');
    }

    private function avatarColor(int $index): string
    {
        return ['#25d366', '#00bcd4', '#ff7043', '#ab47bc', '#ffa726', '#ef5350'][$index % 6];
    }

    private function chatPanelDefinitions(): array
    {
        return [
            'team' => ['label' => 'Alpha / Giga', 'short' => 'AG'],
            'failed' => ['label' => 'Failed', 'short' => 'F'],
            'ticket' => ['label' => 'Ticket', 'short' => 'T'],
            'order' => ['label' => 'Order', 'short' => 'O'],
            'working' => ['label' => 'Working', 'short' => 'W'],
        ];
    }

    private function enabledPanelKeys(?int $userId, array $defaultKeys): array
    {
        if (! $userId) {
            return $defaultKeys;
        }

        $saved = WhatsappChatPanelSetting::query()
            ->where('user_id', $userId)
            ->get();

        if ($saved->isEmpty()) {
            return $defaultKeys;
        }

        $enabled = $saved->where('is_enabled', true)->pluck('panel_key')->values();

        if ($enabled->contains('alpha') || $enabled->contains('giga')) {
            $enabled = $enabled->reject(fn ($key) => in_array($key, ['alpha', 'giga'], true))->push('team');
        }

        return $enabled->unique()->values()->all();
    }

    private function panelRows(string $panelKey)
    {
        $query = Order::query()
            ->select('id', 'order_id', 'title', 'order_date', 'delivery_date', 'projectstatus', 'feedback_ticket', 'team_id')
            ->orderByDesc('id')
            ->limit(30);

        if ($panelKey === 'team') {
            $query->whereIn('team_id', [1, 2]);
        } elseif ($panelKey === 'failed') {
            $query->where(function ($q) {
                $q->where('projectstatus', 'Failed');
                if (Schema::hasColumn('orders', 'is_fail')) {
                    $q->orWhere('is_fail', 1);
                }
            });
        } elseif ($panelKey === 'ticket') {
            $query->whereNotNull('feedback_ticket')->where('feedback_ticket', '!=', '');
        } elseif ($panelKey === 'working') {
            $query->whereNotIn('projectstatus', ['Completed', 'Delivered', 'Cancelled', 'Feedback', 'Feedback Delivered']);
        }

        return $query->get();
    }

    public function importContacts(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'contacts'          => ['required', 'array', 'min:1', 'max:100'],
            'contacts.*.name'   => ['required', 'string', 'max:200'],
            'contacts.*.phone'  => ['required', 'string', 'max:30'],
        ]);

        $imported = 0;
        $failed   = 0;

        foreach ($validated['contacts'] as $contact) {
            try {
                $phone = trim($contact['phone']);
                $name  = trim($contact['name']);

                if (!str_starts_with($phone, '+')) {
                    $phone = '+' . ltrim(preg_replace('/\D+/', '', $phone), '+');
                }

                if (strlen($phone) < 8) {
                    $failed++;
                    continue;
                }

                WhatsappMessage::query()->firstOrCreate(
                    ['phone' => $phone, 'message' => ''],
                    ['name' => $name, 'direction' => 'outbound', 'status' => 'draft']
                );

                $imported++;
            } catch (\Throwable $e) {
                Log::warning('WhatsApp import contact failed', ['contact' => $contact, 'error' => $e->getMessage()]);
                $failed++;
            }
        }

        return response()->json([
            'success'  => true,
            'imported' => $imported,
            'failed'   => $failed,
            'message'  => "{$imported} contact(s) imported.",
        ]);
    }

    public function sendMedia(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'file'  => ['nullable', 'file', 'max:51200'],
            'files' => ['nullable', 'array', 'max:20'],
            'files.*' => ['file', 'max:51200'],
            'caption' => ['nullable', 'string', 'max:1000'],
        ]);

        $phone    = $validated['phone'];
        $caption  = $validated['caption'] ?? '';

        $files = collect($request->file('files', []));
        if ($request->hasFile('file')) {
            $files = $files->prepend($request->file('file'));
        }

        $files = $files->filter()->values();

        if ($files->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one file.',
            ], 422);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'heif', 'bmp', 'mp4', 'mov', 'avi', 'mkv', 'webm', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar', 'mp3', 'ogg', 'wav', 'm4a', 'csv'];
        $messages = collect();

        foreach ($files as $index => $file) {
            $extension = strtolower($file->getClientOriginalExtension());

            if (! in_array($extension, $allowedExtensions, true)) {
                return response()->json([
                    'success' => false,
                    'message' => "{$file->getClientOriginalName()} file type is not allowed.",
                ], 422);
            }

            $origName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType() ?: $file->getClientMimeType();
            $size     = $file->getSize();

            $mediaType = match (true) {
                str_starts_with((string) $mimeType, 'image/') => 'image',
                str_starts_with((string) $mimeType, 'video/') => 'video',
                str_starts_with((string) $mimeType, 'audio/') => 'audio',
                default                                      => 'document',
            };

            $fileName = uniqid('wa_', true) . '.' . $extension;
            $destinationPath = base_path('assets/media/whatsapp');

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $relativePath = 'assets/media/whatsapp/' . $fileName;

            $message = WhatsappMessage::query()->create([
                'phone'      => $phone,
                'name'       => Auth::user()?->name ?? 'Admin',
                'message'    => $index === 0 ? $caption : '',
                'direction'  => 'outbound',
                'status'     => 'queued',
                'media_url'  => $relativePath,
                'media_type' => $mediaType,
                'media_name' => $origName,
                'media_size' => $size,
            ]);

            $this->sendViaActiveProvider($message);
            $message->refresh();
            $messages->push($message);
        }

        return response()->json([
            'success'  => true,
            'message'  => $this->messagePayload($messages->first()),
            'messages' => $messages->map(fn (WhatsappMessage $message) => $this->messagePayload($message))->values(),
            'contacts' => $this->getContacts($phone),
        ]);
    }

    private function providerMediaUrl(string $mediaUrl): string
    {
        if (str_starts_with($mediaUrl, 'http://') || str_starts_with($mediaUrl, 'https://')) {
            return $mediaUrl;
        }

        $publicBaseUrl = rtrim(env('WHATSAPP_PUBLIC_URL', config('app.url')), '/');

        return $publicBaseUrl . '/' . ltrim($mediaUrl, '/');
    }
}
