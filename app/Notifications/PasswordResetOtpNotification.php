<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetOtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $otp,
        private readonly string $brand = 'ain'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isPrime = strtolower((string)$this->brand) === 'prime';

        $subject = $isPrime
            ? 'Your Prime Assignment password reset OTP'
            : 'Your AIN password reset OTP';

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->view('mail.password-reset-otp', [
                'name' => $notifiable->name ?: 'there',
                'otp' => $this->otp,
                'expiresIn' => 10,
                'brand' => $this->brand,
                'isPrime' => $isPrime,
            ]);

        if ($isPrime) {
            $fromName = env('PRIME_MAIL_FROM_NAME', 'Prime Assignment');
            $fromAddress = env('PRIME_MAIL_FROM_ADDRESS', config('mail.from.address'));
            if (!empty($fromAddress)) {
                $mailMessage->from($fromAddress, $fromName);
            }
        }

        return $mailMessage;
    }
}
