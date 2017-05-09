<?php

namespace App\Notifications\Account;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
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
//        $url = url('password/reset/'.$this->token);
        $url = url(config('app.url').route('password.reset', $this->token, false));
        return (new MailMessage)
            ->subject('Restablecer contraseña')
            ->greeting('Hola!')
            ->line('Estas recibiendo este correo porque se ha hecho una solicitud para restablecer la contraseña para tu cuenta.')
            ->action('Restablecer contraseña', $url)
            ->line('Si no hiciste esta solicitud de cambio de contraseña, no es necesario que hagas nada más.');
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
