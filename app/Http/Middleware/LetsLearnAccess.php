<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LetsLearnAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access only if the authenticated user has role_id = 1
        if (!Auth::check() || Auth::user()->role_id != 1) {
            // Redirect to a 404 page if the user does not have the correct role
            return redirect('/');
        }

        // Allow the request to proceed
        return $next($request);
    }
}
