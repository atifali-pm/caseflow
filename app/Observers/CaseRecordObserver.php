<?php

namespace App\Observers;

use App\Enums\CaseStatus;
use App\Models\CaseRecord;
use App\Services\WebhookDispatcher;

class CaseRecordObserver
{
    public function created(CaseRecord $case): void
    {
        WebhookDispatcher::dispatch('case.created', $case->provider_id, [
            'id' => $case->id,
            'title' => $case->title,
            'client_id' => $case->client_id,
            'status' => $case->status->value,
            'stage' => $case->stage->value,
        ]);
    }

    public function updated(CaseRecord $case): void
    {
        WebhookDispatcher::dispatch('case.updated', $case->provider_id, [
            'id' => $case->id,
            'changes' => $case->getChanges(),
        ]);

        if ($case->wasChanged('status') && $case->status === CaseStatus::Closed) {
            WebhookDispatcher::dispatch('case.closed', $case->provider_id, [
                'id' => $case->id,
                'title' => $case->title,
            ]);
        }
    }
}
