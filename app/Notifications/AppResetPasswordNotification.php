<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $baseUrl = rtrim(config('services.mobile_app.password_reset_url'), '?&');
        $separator = str_contains($baseUrl, '?') ? '&' : '?';
        $resetUrl = $baseUrl.$separator.http_build_query([
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Reset your AIN app password')
            ->view('mail.app-reset-password', [
                'name' => $notifiable->name ?: 'there',
                'email' => $notifiable->getEmailForPasswordReset(),
                'resetUrl' => $resetUrl,
                'expiresIn' => config('auth.passwords.users.expire'),
            ]);
    }
}
