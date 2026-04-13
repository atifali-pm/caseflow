<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\CaseRecord;
use App\Models\Client;
use App\Models\Scopes\ProviderScope;

class PortalController extends Controller
{
    public function dashboard()
    {
        $client = $this->getClient();

        $cases = CaseRecord::withoutGlobalScope(ProviderScope::class)
            ->where('client_id', $client->id)
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('portal.dashboard', compact('client', 'cases'));
    }

    public function cases()
    {
        $client = $this->getClient();

        $cases = CaseRecord::withoutGlobalScope(ProviderScope::class)
            ->where('client_id', $client->id)
            ->latest('updated_at')
            ->paginate(10);

        return view('portal.cases.index', compact('client', 'cases'));
    }

    public function showCase(CaseRecord $caseRecord)
    {
        $client = $this->getClient();

        abort_unless($caseRecord->client_id === $client->id, 403);

        $caseRecord->load(['milestones', 'documents.uploader', 'messages.sender']);

        return view('portal.cases.show', ['case' => $caseRecord, 'client' => $client]);
    }

    public function invoices()
    {
        return view('portal.invoices.index');
    }

    protected function getClient(): Client
    {
        return Client::withoutGlobalScope(ProviderScope::class)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }
}
