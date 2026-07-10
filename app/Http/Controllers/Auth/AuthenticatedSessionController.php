<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginOtpNotification;
use App\Models\LoginOtpSystemBan;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $isLocalAdminLogin = app()->environment('local')
            && strtolower((string) $request->input('email')) === 'admin@gmail.com';

        if ($isLocalAdminLogin) {
            $user = $request->authenticate();

            if ((int) $user->role_id !== 1) {
                Auth::logout();

                return redirect()->route('login')
                    ->with('warning', 'Local login account is not an admin.');
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            return $this->redirectAfterLogin($user);
        }

        if ($ban = $this->activeSystemBan($request->ip())) {
            return redirect()->route('login')
                ->with('warning', $this->systemBanMessage($ban));
        }

        $user = $request->authenticate();

        if ((int) $user->role_id === 1) {
            try {
                $this->createAdminEmailOtpNotification($request, $user);
            } catch (\Throwable $e) {
                \Log::error('Admin OTP email failed: ' . $e->getMessage());

                return redirect()->route('login')
                    ->with('warning', 'Admin OTP email could not be sent. SMTP username/password is incorrect.');
            }

            return redirect()->route('login.otp')
                ->with('warning', 'Admin OTP has been sent to singhmahipal23@gmail.com.');
        }

        if ((int) $user->role_id !== 1) {
            $this->createPendingOtpNotification($request, $user);

            return redirect()->route('login.otp')
                ->with('warning', 'Admin OTP approval is required before login.');
        }

        return redirect()->route('login')
            ->with('warning', 'Please log in to access this page.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function doTakeover(Request $request): RedirectResponse
    {
        $userId = session('takeover_user_id');
        $credentials = session('takeover_credentials');

        if (!$userId || !$credentials) {
            return redirect()->route('login')
                ->with('warning', 'Takeover session expired.');
        }

        if ($ban = $this->activeSystemBan($request->ip())) {
            return redirect()->route('login')
                ->with('warning', $this->systemBanMessage($ban));
        }

        DB::table('sessions')->where('user_id', $userId)->delete();

        $user = User::find($userId);

        if (!$user || !Auth::validate($credentials)) {
            Session::forget(['takeover_user_id', 'takeover_credentials']);

            return redirect()->route('login')
                ->with('warning', 'Takeover login failed.');
        }

        if ((int) $user->role_id !== 1) {
            $this->createPendingOtpNotification($request, $user);
            Session::forget(['takeover_user_id', 'takeover_credentials']);

            return redirect()->route('login.otp')
                ->with('warning', 'Admin OTP approval is required before login.');
        }

        try {
            $this->createAdminEmailOtpNotification($request, $user);
        } catch (\Throwable $e) {
            \Log::error('Admin OTP email failed: ' . $e->getMessage());

            return redirect()->route('login')
                ->with('warning', 'Admin OTP email could not be sent. SMTP username/password is incorrect.');
        }

        Session::forget(['takeover_user_id', 'takeover_credentials']);

        return redirect()->route('login.otp')
            ->with('warning', 'Admin OTP has been sent to singhmahipal23@gmail.com.');
    }

    public function showOtpForm(): View|RedirectResponse
    {
        if ($ban = $this->activeSystemBan(request()->ip())) {
            Session::forget(['pending_login_otp_id', 'pending_login_remember']);

            return redirect()->route('login')
                ->with('warning', $this->systemBanMessage($ban));
        }

        $notification = $this->pendingOtpNotification();

        if (!$notification) {
            return redirect()->route('login')
                ->with('warning', 'Please login again to request admin OTP approval.');
        }

        return view('auth.login-otp', compact('notification'));
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $notification = $this->pendingOtpNotification();

        if (!$notification) {
            return redirect()->route('login')
                ->with('warning', 'OTP request expired. Please login again.');
        }

        if ($ban = $this->activeSystemBan($request->ip())) {
            Session::forget(['pending_login_otp_id', 'pending_login_remember']);

            return redirect()->route('login')
                ->with('warning', $this->systemBanMessage($ban));
        }

        if (!hash_equals($notification->otp_code, (string) $request->otp_code)) {
            $failedAttempts = $notification->failed_attempts + 1;

            $notification->update([
                'failed_attempts' => $failedAttempts,
                'last_failed_at' => now(),
            ]);

            if ($failedAttempts > 3) {
                $this->banSystem($notification->ip_address ?: $request->ip(), $notification->user_id, false, 'Wrong OTP attempts limit crossed.');

                $notification->update([
                    'status' => 'blocked',
                    'blocked_at' => now(),
                ]);

                Session::forget(['pending_login_otp_id', 'pending_login_remember']);

                return redirect()->route('login')
                    ->with('warning', 'Wrong OTP attempts limit crossed. This system is banned for 24 hours.');
            }

            $remainingAttempts = max(0, 3 - $failedAttempts);

            return back()->withErrors([
                'otp_code' => 'Invalid OTP. Remaining attempts: ' . $remainingAttempts,
            ]);
        }

        $notification->update([
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        Auth::login($notification->user, true);

        $request->session()->regenerate();
        Session::forget(['pending_login_otp_id', 'pending_login_remember']);

        return $this->redirectAfterLogin($notification->user);
    }

    public function loginOtpNotifications(Request $request): View
    {
        abort_unless(auth()->check() && (int) auth()->user()->role_id === 1, 403);

        $notifications = LoginOtpNotification::with('user')
            ->when($request->filled('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->filled('ip_address'), function ($query) use ($request) {
                $query->where('ip_address', $request->ip_address);
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $users = User::whereIn('id', LoginOtpNotification::select('user_id'))
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $bans = LoginOtpSystemBan::whereNull('unbanned_at')
            ->where(function ($query) {
                $query->whereNull('banned_until')
                    ->orWhere('banned_until', '>', now());
            })
            ->get()
            ->keyBy('ip_address');

        return view('auth.login-otp-notifications', compact('notifications', 'users', 'bans'));
    }

    public function banOtpSystem(Request $request): RedirectResponse
    {
        abort_unless(auth()->check() && (int) auth()->user()->role_id === 1, 403);

        $request->validate([
            'ip_address' => ['required', 'string', 'max:45'],
            'user_id' => ['nullable', 'integer'],
        ]);

        $this->banSystem($request->ip_address, $request->user_id, true, 'Manual admin ban.');

        return back()->with('success', 'System banned for 24 hours.');
    }

    public function unbanOtpSystem(Request $request): RedirectResponse
    {
        abort_unless(auth()->check() && (int) auth()->user()->role_id === 1, 403);

        $request->validate([
            'ip_address' => ['required', 'string', 'max:45'],
        ]);

        LoginOtpSystemBan::where('ip_address', $request->ip_address)
            ->whereNull('unbanned_at')
            ->update([
                'unbanned_at' => now(),
                'unbanned_by' => auth()->id(),
            ]);

        return back()->with('success', 'System unbanned successfully.');
    }

    private function createPendingOtpNotification(Request $request, User $user): LoginOtpNotification
    {
        LoginOtpNotification::where('user_id', $user->id)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        $notification = LoginOtpNotification::create([
            'user_id' => $user->id,
            'otp_code' => (string) random_int(100000, 999999),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'status' => 'pending',
            'purpose' => 'user_admin_approval',
            'expires_at' => now()->addMinutes(10),
        ]);

        session([
            'pending_login_otp_id' => $notification->id,
            'pending_login_remember' => true,
        ]);

        return $notification;
    }

    private function createAdminEmailOtpNotification(Request $request, User $user): LoginOtpNotification
    {
        $emailTo = 'singhmahipal23@gmail.com';

        LoginOtpNotification::where('user_id', $user->id)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        $notification = LoginOtpNotification::create([
            'user_id' => $user->id,
            'otp_code' => (string) random_int(100000, 999999),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'status' => 'pending',
            'purpose' => 'admin_email_login',
            'email_to' => $emailTo,
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::raw(
                "Admin login OTP: {$notification->otp_code}\n\nUser: {$user->name}\nEmail: {$user->email}\nIP: {$notification->ip_address}\nValid for 10 minutes.",
                function ($message) use ($emailTo) {
                    $message->to($emailTo)
                        ->subject('AIN Admin Login OTP');
                }
            );
        } catch (\Throwable $e) {
            $notification->update(['status' => 'email_failed']);
            throw $e;
        }

        session([
            'pending_login_otp_id' => $notification->id,
            'pending_login_remember' => true,
        ]);

        return $notification;
    }

    private function activeSystemBan(?string $ipAddress): ?LoginOtpSystemBan
    {
        if (!$ipAddress) {
            return null;
        }

        return LoginOtpSystemBan::where('ip_address', $ipAddress)
            ->whereNull('unbanned_at')
            ->where(function ($query) {
                $query->whereNull('banned_until')
                    ->orWhere('banned_until', '>', now());
            })
            ->latest()
            ->first();
    }

    private function banSystem(?string $ipAddress, ?int $userId, bool $isManual, string $reason): ?LoginOtpSystemBan
    {
        if (!$ipAddress) {
            return null;
        }

        $ban = LoginOtpSystemBan::firstOrNew([
            'ip_address' => $ipAddress,
            'unbanned_at' => null,
        ]);

        $ban->fill([
            'user_id' => $userId,
            'banned_until' => now()->addDay(),
            'is_manual' => $isManual,
            'attempts_count' => ((int) $ban->attempts_count) + 1,
            'last_attempt_at' => now(),
            'reason' => $reason,
            'banned_by' => auth()->id(),
        ]);

        $ban->save();

        return $ban;
    }

    private function systemBanMessage(LoginOtpSystemBan $ban): string
    {
        $until = $ban->banned_until
            ? $ban->banned_until->format('d M Y h:i A')
            : 'further notice';

        return 'This system is banned for OTP login until ' . $until . '. Please contact admin.';
    }

    private function pendingOtpNotification(): ?LoginOtpNotification
    {
        $notificationId = session('pending_login_otp_id');

        if (!$notificationId) {
            return null;
        }

        $notification = LoginOtpNotification::with('user')->find($notificationId);

        if (!$notification || !$notification->isUsable()) {
            Session::forget(['pending_login_otp_id', 'pending_login_remember']);
            return null;
        }

        return $notification;
    }

    private function redirectAfterLogin(User $user): RedirectResponse
    {
        $message = 'Welcome back, ' . $user->name . '.';

        if ($user->role_id == 4 || $user->role_id == 9) {
            return redirect()->intended(RouteServiceProvider::HOME)
                ->with('success', $message);
        }

        return redirect(RouteServiceProvider::HOME)
            ->with('success', $message);
    }
}
