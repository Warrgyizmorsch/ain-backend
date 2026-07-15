<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Google\Auth\AccessToken as GoogleAccessToken;


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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'mobile_no' => $request->phone_no,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
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
        try {

            $request->validate([
                'email' => 'required|email'
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );
            

            return response()->json([
                'success' => $status === Password::RESET_LINK_SENT,
                'message' => __($status)
            ], 200);

        } catch (\Exception $e) {

            \Log::error('Forgot Password Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Unable to send reset password email.',
                'error' => $e->getMessage() // Production me hata dena
            ], 500);
        }
    }

    // RESET PASSWORD API

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => __($status)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __($status)
        ], 400);
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $mobile = preg_replace('/\D/', '', $request->mobile_no);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_no' => $mobile,
            'countrycode' => $request->countrycode,
            'country' => $request->country,
        ]);

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
            ]
        ]);
    }

    // REFER LIST API
    public function referList(Request $request)
    {
        $user = $request->user();

        $referredUsers = \App\Models\User::where('refer_id', $user->id)
            ->select('id', 'name', 'email', 'mobile_no', 'countrycode', 'created_at')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $referredUsers->count(),
            'data' => $referredUsers
        ]);
    }

    public function googleLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
            'email' => 'nullable|email',
            'name' => 'nullable|string|max:255',
            'mobile_no' => 'nullable|string|max:20',
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

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name ?: Str::before($email, '@'),
                    'password' => Hash::make('user@123'),
                    'role_id' => 2,
                    'mobile_no' => $mobile,
                ]
            );

            $registered = $user->wasRecentlyCreated;

            if (!$registered && $mobile && !$user->mobile_no) {
                $user->mobile_no = $mobile;
                $user->save();
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

            return response()->json([
                'success' => false,
                'message' => 'Invalid Google Token',
            ],401);

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
            $payload = count($segments) === 3
                ? json_decode(base64_decode(strtr($segments[1], '-_', '+/')), true)
                : null;
            $audience = is_array($payload) ? ($payload['aud'] ?? null) : null;
            $allowedClientIds = config('services.google.client_ids', []);

            if (!$audience || !in_array($audience, $allowedClientIds, true)) {
                throw new \RuntimeException('Google token audience is not allowed.');
            }

            $claims = (new GoogleAccessToken())->verify($idToken, [
                'audience' => $audience,
                'issuer' => GoogleAccessToken::OAUTH2_ISSUER_HTTPS,
                'throwException' => true,
            ]);

            if (empty($claims['email']) || empty($claims['email_verified'])) {
                throw new \RuntimeException('Google account email is not verified.');
            }

            return [strtolower($claims['email']), $claims['name'] ?? null];
        }
    }
}
