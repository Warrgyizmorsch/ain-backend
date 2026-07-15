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
            ->greeting('Hello '.$notifiable->name.',')
            ->line('We received a password reset request for your AIN mobile app account.')
            ->action('Reset Password in App', $resetUrl)
            ->line('This secure link expires in '.config('auth.passwords.users.expire').' minutes.')
            ->line('If you did not request a password reset, you can safely ignore this email.');
    }
}
