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
        $resetUrl = rtrim(config('services.password_reset.web_url'), '/')
            .'/'.rawurlencode($this->token)
            .'?'.http_build_query([
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
