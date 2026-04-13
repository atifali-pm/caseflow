<?php

namespace App\Notifications;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientPortalInvitation extends Notification
{
    use Queueable;

    public function __construct(public Client $client)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('portal.register', $this->client->invitation_token);

        return (new MailMessage)
            ->subject('You have been invited to the CaseFlow portal')
            ->greeting("Hello {$this->client->first_name},")
            ->line('Your provider has invited you to the CaseFlow client portal.')
            ->line('You can view your cases, upload documents, and message your provider.')
            ->action('Accept Invitation', $url)
            ->line('If you did not expect this invitation, please ignore this email.');
    }
}
