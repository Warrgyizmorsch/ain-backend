<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat.{phone}', function ($user, $phone) {
    return true; // 🔐 TEMPORARILY allow anyone
});

Broadcast::channel('whatsapp.chat', function ($user) {
    return true;
});

Broadcast::channel('emails.account.{accountId}', function ($user, $accountId) {
    return $user !== null;
});
