<?php

namespace App\Services;

use App\Models\Webhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookDispatcher
{
    public static function dispatch(string $event, int $providerId, array $payload): void
    {
        $webhooks = Webhook::withoutGlobalScopes()
            ->where('provider_id', $providerId)
            ->where('active', true)
            ->get()
            ->filter(fn ($w) => in_array($event, $w->events ?? []));

        foreach ($webhooks as $webhook) {
            try {
                $body = json_encode([
                    'event' => $event,
                    'timestamp' => now()->toIso8601String(),
                    'data' => $payload,
                ]);

                $signature = hash_hmac('sha256', $body, $webhook->secret);

                Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'X-CaseFlow-Signature' => $signature,
                    'X-CaseFlow-Event' => $event,
                ])->timeout(5)->send('POST', $webhook->url, ['body' => $body]);

                $webhook->update(['last_triggered_at' => now()]);
            } catch (\Exception $e) {
                Log::warning("Webhook dispatch failed: {$e->getMessage()}", [
                    'webhook_id' => $webhook->id,
                    'event' => $event,
                ]);
            }
        }
    }
}
