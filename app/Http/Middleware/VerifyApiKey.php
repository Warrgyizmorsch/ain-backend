<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = env('API_KEY');

        // Check X-Api-Key header if API_KEY is set in the environment (.env)
        if (!empty($apiKey) && $request->header('X-Api-Key') !== $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized API request. Invalid X-Api-Key.'
            ], 401);
        }

        return $next($request);
    }
}
