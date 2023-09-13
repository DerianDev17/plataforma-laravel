<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendCredentials extends Notification
{
    use Queueable;
    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user_name = $this->user->username;
        // dd($this->user->username);
        return (new MailMessage)
            ->line('¡Hola! Bienvenido/a a EUS3 Preuniversitario.')
            ->line('Gracias por registrarte en la plataforma virtual de EUS3 Preuniversitario, desde ahora podrás vivir la mejor experiencia en educación virtual. ')
            ->line('Debe acceder con los siguientes datos:')
            ->line('Nombre de usuario:'.' '.$user_name)
            ->line('Contraseña:'.' '.$user_name)
            // ->action('Notification Action', url('/'))
            ->line('¡Nosotros te ayudamos a cumplir tus sueños!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
