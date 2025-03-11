<?php
// app/Notifications/WelcomeNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to MovieTick!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Welcome to MovieTick - your gateway to amazing cinema experiences!')
            ->line('We\'re thrilled to have you join our community of movie enthusiasts.')
            ->line('With MovieTick, you can:')
            ->line('• Book tickets for the latest movies')
            ->line('• Choose your preferred seats')
            ->line('• Get special discounts on movie days')
            ->line('• Manage your bookings easily')
            ->action('Start Exploring Movies', url('/'))
            ->line('Thank you for choosing MovieTick. Get ready for an incredible cinema journey!');
    }
}
