<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendCredentials extends Notification
{
    use Queueable;

    public $user;
    public $tempPassword;

    public function __construct(User $user, ?string $tempPassword = null)
    {
        $this->user = $user;
        $this->tempPassword = $tempPassword;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->line('Hola. Bienvenido/a a Semilla Digital.')
            ->line('Debe acceder con los siguientes datos:')
            ->line('Nombre de usuario: ' . $this->user->username);

        if ($this->tempPassword) {
            $message->line('Contrasena temporal: ' . $this->tempPassword);
        } else {
            $message->line('Su contrasena actual no cambia. Si no la recuerda, use la recuperacion de contrasena.');
        }

        return $message->line('Semilla Digital.');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
