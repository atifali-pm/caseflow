<?php

namespace App\Notifications;

use App\Models\Task;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    public function __construct(public Task $task)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Task assigned')
            ->body($this->task->title)
            ->icon('heroicon-o-check-circle')
            ->getDatabaseMessage();
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New task assigned: ' . $this->task->title)
            ->line('A new task has been assigned to you.')
            ->line('Title: ' . $this->task->title)
            ->when($this->task->due_date, fn ($mail) => $mail->line('Due: ' . $this->task->due_date->format('M j, Y')))
            ->action('View Task', url('/admin/tasks/' . $this->task->id . '/edit'));
    }
}
