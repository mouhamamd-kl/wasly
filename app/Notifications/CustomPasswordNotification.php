<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomPasswordNotification extends Notification
{
    use Queueable;
    public $token;
    public $route;
    public $email;
    /**
     * Create a new notification instance.
     */
    public function __construct($token, $route, $email)
    {
        $this->token = $token;
        $this->route = $route;
        $this->email = $email;
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
        $url = url("api/{$this->route}/reset-password/{$this->token}?email={$this->email}");
        return (new MailMessage)

            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Notification Action', url($url))
            ->line('This password reset link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required.');
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
