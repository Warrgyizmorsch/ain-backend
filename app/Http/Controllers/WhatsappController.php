<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WhatsappMessage;
use App\Models\WhatsappSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        $contacts = $this->getContacts($request->query('phone'));
        $selectedContact = collect($contacts)->firstWhere('active', true) ?? collect($contacts)->first();
        $selectedPhone = $selectedContact['phone'] ?? null;

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
        ]);
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

    public function sendMessage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = $this->storeOutboundMessage($validated['phone'], $validated['message']);
        $this->sendViaActiveProvider($message);

        return redirect()->route('whatsapp.chat', ['phone' => $validated['phone']]);
    }

    private function getContacts(?string $activePhone): array
    {
        $latestMessages = DB::table('whatsapp_messages as latest')
            ->join(DB::raw('(SELECT phone, MAX(id) as max_id FROM whatsapp_messages GROUP BY phone) as grouped'), function ($join) {
                $join->on('latest.id', '=', 'grouped.max_id');
            })
            ->select('latest.phone', 'latest.name', 'latest.message', 'latest.created_at', 'latest.id')
            ->orderByDesc('latest.id')
            ->get();

        if (! $activePhone && $latestMessages->isNotEmpty()) {
            $activePhone = $latestMessages->first()->phone;
        }

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
                'msg' => $contact->message ?: 'New chat started',
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
            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'From' => str_starts_with($from, 'whatsapp:') ? $from : 'whatsapp:' . $from,
                    'To' => str_starts_with($message->phone, 'whatsapp:') ? $message->phone : 'whatsapp:' . $message->phone,
                    'Body' => $message->message,
                ]);

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
}
