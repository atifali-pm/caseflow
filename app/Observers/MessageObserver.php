<?php

namespace App\Observers;

use App\Models\Message;
use App\Notifications\NewMessageNotification;

class MessageObserver
{
    public function created(Message $message): void
    {
        $caseRecord = $message->caseRecord;
        if (! $caseRecord) {
            return;
        }

        if ($message->sender_id === $caseRecord->provider_id) {
            $client = $caseRecord->client;
            if ($client?->user_id) {
                $client->user->notify(new NewMessageNotification($message));
            }
            return;
        }

        $caseRecord->provider?->notify(new NewMessageNotification($message));
    }
}
