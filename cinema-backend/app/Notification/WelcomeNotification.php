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
            ->subject('Benvingut a MoviieTick!')
            ->greeting('Hola ' . $notifiable->name . '!')
            ->line('Benvingut a MoviieTick - la teva porta d\'entrada a experiències de cinema increïbles!')
            ->line('Estem encantats que t\'uneixis a la nostra comunitat d\'entusiastes de cinema.')
            ->line('Amb MoviieTick, pots:')
            ->line('• Reservar entrades per a les últimes pel·lícules')
            ->line('• Escollir els teus seients preferits')
            ->line('• Aconseguir descomptes especials els dies de cinema')
            ->line('• Gestionar fàcilment les teves reserves')
            ->action('Comença a explorar pel·lícules', config('app.url'))
            ->line('Gràcies per triar MoviieTick. Prepara\'t per a un viatge cinematogràfic increïble');
    }
}
