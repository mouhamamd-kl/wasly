<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OTPEmailVerification extends Notification
{
    use Queueable;
    public $otp;
    /**
     * Create a new notification instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
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
        return (new MailMessage)
            ->subject('Your OTP Verification Code') // Set email subject
            ->greeting('Hello!') // Optional greeting
            ->line('Your OTP for email verification is:') // Introductory line
            ->line($this->otp) // The actual OTP
            ->line('Please enter this OTP in the application to verify your email address.') // Instructions
            ->line('This OTP is valid for the next 10 minutes.') // Validity duration
            ->action('Verify Email', url('/')) // Optional action button (customize the URL if needed)
            ->line('Thank you for using our application!'); // Closing line
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
