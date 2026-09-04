<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'whatsapp/settings',
        'whatsapp/chat/*',
        'emails/labels/*',
        'emails/contacts/*',
        'emails/toggle-star',
        'emails/mark-read',
        'emails/delete',
        'emails/sync',
        'api/*',
        'webhook/emails/inbound',
    ];
}
