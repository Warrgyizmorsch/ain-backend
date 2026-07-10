<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GzipCompression
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Downloads and streamed responses do not expose mutable string
        // content. Compressing them here corrupts the file and Symfony throws
        // "The content cannot be set on a StreamedResponse instance."
        if ($response instanceof StreamedResponse || $response instanceof BinaryFileResponse) {
            return $response;
        }

        $acceptsGzip = str_contains((string) $request->header('Accept-Encoding', ''), 'gzip');
        $alreadyEncoded = $response->headers->has('Content-Encoding');

        if ($acceptsGzip && !$alreadyEncoded && $response->getContent() !== false) {
            $response->headers->set('Content-Encoding', 'gzip');
            $response->setContent(gzencode($response->getContent()));
            $response->headers->remove('Content-Length');
            $response->headers->set('Vary', 'Accept-Encoding');
        }

        return $response;
    }
}
