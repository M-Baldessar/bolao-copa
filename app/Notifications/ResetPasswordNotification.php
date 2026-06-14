<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public function __construct(public string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('🔐 Redefinição de senha — Bolão Copa 2026')
            ->view('emails.reset-password', [
                'url'  => $url,
                'name' => $notifiable->name,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
