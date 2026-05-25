<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('warning', 'Please log in to access this page.');
        }

        if ($user->role_id == 4 || $user->role_id == 9) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        if ($user->role_id == 2) {
            return redirect('/MyOrders');
        }

        return redirect(RouteServiceProvider::HOME);
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

        DB::table('sessions')->where('user_id', $userId)->delete();

        if (!Auth::attempt($credentials)) {
            Session::forget(['takeover_user_id', 'takeover_credentials']);

            return redirect()->route('login')
                ->with('warning', 'Takeover login failed.');
        }

        $request->session()->regenerate();

        Session::forget(['takeover_user_id', 'takeover_credentials']);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}