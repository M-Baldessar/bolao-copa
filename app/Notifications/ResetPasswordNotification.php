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
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Recebemos uma solicitação para redefinir a senha da sua conta no **Bolão Copa 2026**.')
            ->line('Clique no botão abaixo para criar uma nova senha. O link é válido por **60 minutos**.')
            ->action('Redefinir minha senha', $url)
            ->line('Se você não solicitou a redefinição de senha, pode ignorar este e-mail com segurança — sua senha não será alterada.')
            ->salutation('Abraços, equipe Bolão Copa 2026 ⚽');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
