<?php

namespace App\Notifications;

use App\Models\Invoice;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaidNotification extends Notification
{
    public function __construct(public Invoice $invoice)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Invoice paid')
            ->body("{$this->invoice->number} for $" . number_format($this->invoice->total, 2))
            ->icon('heroicon-o-banknotes')
            ->color('success')
            ->getDatabaseMessage();
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Invoice paid: ' . $this->invoice->number)
            ->line('Good news, an invoice has been paid.')
            ->line('Invoice: ' . $this->invoice->number)
            ->line('Amount: $' . number_format($this->invoice->total, 2))
            ->action('View Invoice', url('/admin/invoices/' . $this->invoice->id . '/edit'));
    }
}
