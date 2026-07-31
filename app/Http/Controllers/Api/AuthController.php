<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Google\Auth\AccessToken as GoogleAccessToken;
use Illuminate\Support\Facades\Http;
use App\Notifications\PasswordResetOtpNotification;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }
    
    // REGISTER API
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
            'referral_code' => 'nullable|string',
            'refer_id' => 'nullable|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find Referrer User if referral_code or refer_id provided
        $referId = null;
        if ($request->filled('refer_id')) {
            $referId = $request->input('refer_id');
        } elseif ($request->filled('referral_code')) {
            $refCode = trim($request->input('referral_code'));
            $referrer = User::where('referral_code', $refCode)->orWhere('id', $refCode)->first();
            if ($referrer) {
                $referId = $referrer->id;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'mobile_no' => $request->phone_no,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
            'refer_id' => $referId,
            'referral_code' => User::generateUniqueReferralCode(),
        ]);

        $token = $user->createToken('app_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Register successfully',
            'token' => $token,
            'data' => $user
        ], 201);
    }

    // LOGIN API    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $token = $user->createToken('app_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successfully',
            'token' => $token,
            'data' => $user
        ]);
    }

    // FORGOT PASSWORD API
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', strtolower($request->email))->first();

        // Do not reveal whether an email address is registered.
        if (!$user) {
            return response()->json([
                'success' => true,
                'message' => 'If this email is registered, a password reset link has been sent.',
            ]);
        }

        try {
            $otp = (string) random_int(100000, 999999);

            DB::table('password_reset_otps')->updateOrInsert(
                ['email' => strtolower($user->email)],
                [
                    'otp_hash' => Hash::make($otp),
                    'attempts' => 0,
                    'expires_at' => now()->addMinutes(10),
                    'verified_at' => null,
                    'reset_token_hash' => null,
                    'reset_token_expires_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $user->notify(new PasswordResetOtpNotification($otp));

            return response()->json([
                'success' => true,
                'message' => 'Password reset OTP has been sent to your email.',
                'expires_in' => 600,
            ]);
        } catch (\Throwable $e) {
            \Log::error('App forgot password email failed.', [
                'user_id' => $user->id,
                'exception' => get_class($e),
                'reason' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to send reset password email. Please try again later.',
            ], 500);
        }
    }

    public function verifyForgotPasswordOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
        ]);

        $email = strtolower($request->email);
        $record = DB::table('password_reset_otps')->where('email', $email)->first();

        if (!$record || now()->greaterThan($record->expires_at) || $record->attempts >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'OTP is invalid or expired. Please request a new OTP.',
            ], 422);
        }

        if (!Hash::check($request->otp, $record->otp_hash)) {
            DB::table('password_reset_otps')->where('email', $email)->increment('attempts');

            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP.',
                'attempts_remaining' => max(0, 4 - $record->attempts),
            ], 422);
        }

        $resetToken = Str::random(64);
        DB::table('password_reset_otps')->where('email', $email)->update([
            'verified_at' => now(),
            'reset_token_hash' => hash('sha256', $resetToken),
            'reset_token_expires_at' => now()->addMinutes(15),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.',
            'reset_token' => $resetToken,
            'expires_in' => 900,
        ]);
    }

    // RESET PASSWORD API

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reset_token' => 'required|string|size:64',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = strtolower($request->email);
        $record = DB::table('password_reset_otps')->where('email', $email)->first();
        $validToken = $record
            && $record->verified_at
            && $record->reset_token_hash
            && now()->lessThanOrEqualTo($record->reset_token_expires_at)
            && hash_equals($record->reset_token_hash, hash('sha256', $request->reset_token));

        if (!$validToken) {
            return response()->json([
                'success' => false,
                'message' => 'Reset token is invalid or expired.',
            ], 422);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unable to reset password.'], 422);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();
        $user->tokens()->delete();
        DB::table('password_reset_otps')->where('email', $email)->delete();
        event(new PasswordReset($user));

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully. Please login with your new password.',
        ]);
    }

    // GET PROFILE API
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile_no' => $user->mobile_no,
                'countrycode' => $user->countrycode,
                'country' => $user->country,
                'role_id' => $user->role_id,
                'photo' => $user->photo ? asset($user->photo) : null,
                'created_at' => $user->created_at,
            ]
        ]);
    }

    // PROFILE UPDATE
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'mobile_no' => 'required|string|max:20',
            'countrycode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $destinationPath = base_path('images/users');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);

            $photoPath = 'images/users/' . $fileName;

            // Delete old photo if it exists
            if ($user->photo && file_exists(base_path($user->photo))) {
                @unlink(base_path($user->photo));
            }
        }

        $mobile = preg_replace('/\D/', '', $request->mobile_no);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile_no' => $mobile,
            'countrycode' => $request->countrycode,
            'country' => $request->country,
        ];

        if ($photoPath) {
            $updateData['photo'] = $photoPath;
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile_no' => $user->mobile_no,
                'countrycode' => $user->countrycode,
                'country' => $user->country,
                'photo' => $user->photo ? asset($user->photo) : null,
            ]
        ]);
    }

    // REFER LIST & REFERRAL INFO API
    public function referList(Request $request)
    {
        $user = $request->user();

        // Ensure user has a referral code
        if (empty($user->referral_code)) {
            $user->referral_code = User::generateUniqueReferralCode();
            $user->save();
        }

        $referredUsers = \App\Models\User::where('refer_id', $user->id)
            ->select('id', 'name', 'email', 'mobile_no', 'countrycode', 'created_at')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($refUser) use ($user) {
                // Find first order of referred user
                $firstOrder = \App\Models\Order::where('uid', $refUser->id)->oldest('id')->first();
                $bonusEarned = $firstOrder ? round((float)$firstOrder->amount * 0.10, 2) : 0.00;

                return [
                    'id' => $refUser->id,
                    'name' => $refUser->name,
                    'email' => $refUser->email,
                    'mobile_no' => $refUser->mobile_no,
                    'countrycode' => $refUser->countrycode,
                    'joined_at' => $refUser->created_at ? $refUser->created_at->format('Y-m-d H:i:s') : null,
                    'has_ordered' => $firstOrder ? true : false,
                    'first_order_id' => $firstOrder ? $firstOrder->order_id : null,
                    'first_order_amount' => $firstOrder ? (float)$firstOrder->amount : 0.00,
                    'referral_bonus_earned' => $bonusEarned,
                ];
            });

        return response()->json([
            'success' => true,
            'referral_code' => $user->referral_code,
            'total_referrals' => $referredUsers->count(),
            'total_referral_earnings' => (float) ($user->total_referral_earnings ?? 0.00),
            'data' => $referredUsers,
            'payload' => $request->all()
        ]);
    }

    public function googleLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
            'email' => 'nullable|email',
            'name' => 'nullable|string|max:255',
            'mobile_no' => 'nullable|string|max:20',
            'referral_code' => 'nullable|string',
            'refer_id' => 'nullable|integer|exists:users,id',
        ]);

        try {

            [$email, $name] = $this->verifiedSocialIdentity($request->id_token);

            if ($request->filled('email') && strtolower($request->email) !== $email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email does not match the verified Google account.',
                ], 422);
            }

            $name = $request->input('name') ?: $name;
            $mobile = $request->filled('mobile_no')
                ? preg_replace('/\D/', '', $request->mobile_no)
                : null;

            // Check if user exists
            $existingUser = User::where('email', $email)->first();

            if (!$existingUser) {
                $referId = null;
                if ($request->filled('refer_id')) {
                    $referId = $request->input('refer_id');
                } elseif ($request->filled('referral_code')) {
                    $refCode = trim($request->input('referral_code'));
                    $referrer = User::where('referral_code', $refCode)->orWhere('id', $refCode)->first();
                    if ($referrer) {
                        $referId = $referrer->id;
                    }
                }

                $user = User::create([
                    'email' => $email,
                    'name' => $name ?: Str::before($email, '@'),
                    'password' => Hash::make('user@123'),
                    'role_id' => 2,
                    'mobile_no' => $mobile,
                    'refer_id' => $referId,
                    'referral_code' => User::generateUniqueReferralCode(),
                ]);
                $registered = true;
            } else {
                $user = $existingUser;
                $registered = false;
                if ($mobile && !$user->mobile_no) {
                    $user->mobile_no = $mobile;
                    $user->save();
                }
            }

            $token = $user->createToken('app_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Google Login Successful',
                'token' => $token,
                'registered' => $registered,
                'data' => $user
            ]);

        } catch (\Exception $e) {
            \Log::warning('Google social login rejected.', [
                'exception' => get_class($e),
                'reason' => $e->getMessage(),
            ]);

            $response = [
                'success' => false,
                'message' => 'Invalid Google Token',
            ];

            if (app()->environment('local')) {
                $response['reason'] = $e->getMessage();
            }

            return response()->json($response, 401);

        }
    }

    /**
     * Accept either a Firebase ID token or a direct Google Sign-In ID token.
     */
    private function verifiedSocialIdentity(string $idToken): array
    {
        try {
            $verified = $this->firebaseAuth->verifyIdToken($idToken);
            $firebaseUser = $this->firebaseAuth->getUser($verified->claims()->get('sub'));

            if (!$firebaseUser->email) {
                throw new \RuntimeException('Google account email is unavailable.');
            }

            return [strtolower($firebaseUser->email), $firebaseUser->displayName];
        } catch (\Throwable $firebaseException) {
            $segments = explode('.', $idToken);
            if (count($segments) !== 3 || strlen($segments[2]) < 300) {
                throw new \RuntimeException('Google ID token is incomplete or truncated.');
            }

            $payload = count($segments) === 3
                ? json_decode(base64_decode(strtr($segments[1], '-_', '+/')), true)
                : null;
            $audience = is_array($payload) ? ($payload['aud'] ?? null) : null;
            $allowedClientIds = config('services.google.client_ids', []);

            if (!$audience || !in_array($audience, $allowedClientIds, true)) {
                throw new \RuntimeException('Google token audience is not allowed.');
            }

            try {
                $claims = (new GoogleAccessToken())->verify($idToken, [
                    'audience' => $audience,
                    'throwException' => true,
                ]);
            } catch (\Throwable $verificationException) {
                if (!str_contains(strtolower($verificationException->getMessage()), 'phpseclib')) {
                    throw $verificationException;
                }

                $response = Http::acceptJson()
                    ->timeout(10)
                    ->get('https://oauth2.googleapis.com/tokeninfo', ['id_token' => $idToken]);

                if (!$response->successful()) {
                    throw new \RuntimeException('Google rejected the ID token.');
                }

                $claims = $response->json();

                if (
                    ($claims['aud'] ?? null) !== $audience
                    || !in_array($claims['iss'] ?? null, [GoogleAccessToken::OAUTH2_ISSUER, GoogleAccessToken::OAUTH2_ISSUER_HTTPS], true)
                    || (int) ($claims['exp'] ?? 0) <= time()
                ) {
                    throw new \RuntimeException('Google ID token claims are invalid.');
                }
            }

            if (
                empty($claims['email'])
                || !filter_var($claims['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN)
            ) {
                throw new \RuntimeException('Google account email is not verified.');
            }

            return [strtolower($claims['email']), $claims['name'] ?? null];
        }
    }
}
