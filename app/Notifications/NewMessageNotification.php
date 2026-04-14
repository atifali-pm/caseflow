<?php

namespace App\Notifications;

use App\Models\Message;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    public function __construct(public Message $message)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('New message')
            ->body("From {$this->message->sender->name} on case {$this->message->caseRecord->title}")
            ->icon('heroicon-o-chat-bubble-left-right')
            ->getDatabaseMessage();
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New message on your case')
            ->line("You have a new message from {$this->message->sender->name}.")
            ->line("Case: {$this->message->caseRecord->title}")
            ->line('"' . $this->message->body . '"')
            ->action('View Case', url('/admin/cases/' . $this->message->case_record_id . '/edit'));
    }
}
