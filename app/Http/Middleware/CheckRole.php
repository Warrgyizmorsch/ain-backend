<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     // Check if user is authenticated and has role_id = 2
    //     if (Auth::check() && Auth::user()->role_id == 2) {
    //         // If yes, deny access
    //         return view('404');
    //     }

    //     // If not, allow access to the route
    //     return $next($request);
    // }
    
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated and has a valid role
        if (Auth::check()) {
            $userRole = Auth::user()->role_id;

            // If the user's role is not in the allowed roles, deny access
            if (!in_array($userRole, $roles)) {
                return view('404');
            }

            // If the user has the correct role, allow the request
            return $next($request);
        }

        // If the user is not authenticated, redirect to login page
        return redirect()->route('login')->with('error', 'Please log in to continue.');
    }
}
