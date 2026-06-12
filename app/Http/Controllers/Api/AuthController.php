<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;


class AuthController extends Controller
{
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
}