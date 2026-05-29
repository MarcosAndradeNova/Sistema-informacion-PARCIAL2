<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FICCTResetPassword extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
                    ->subject('Restablecer Contraseña - Sistema de Admisión FICCT')
                    ->greeting('Hola,')
                    ->line('Se solicitó un cambio de contraseña para su cuenta del Sistema de Admisión FICCT.')
                    ->action('Cambiar Contraseña', $url)
                    ->line('Este enlace de recuperación expirará en 60 minutos.')
                    ->line('Si no solicitaste este cambio, puedes ignorar este correo de forma segura.')
                    ->salutation('Atentamente, Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones (FICCT)');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
