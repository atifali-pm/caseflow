<?php

namespace App\Http\Controllers\Api;

use App\Enums\CasePriority;
use App\Enums\CaseStage;
use App\Enums\CaseStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\CaseRecordResource;
use App\Models\CaseRecord;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $query = CaseRecord::with(['client', 'tags']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($stage = $request->query('stage')) {
            $query->where('stage', $stage);
        }

        if ($search = $request->query('search')) {
            $query->where('title', 'ilike', "%{$search}%");
        }

        return CaseRecordResource::collection($query->paginate($request->query('per_page', 15)));
    }

    public function show(CaseRecord $case)
    {
        $this->authorize('view', $case);
        return new CaseRecordResource($case->load(['client', 'tags', 'milestones']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['nullable', 'string'],
            'stage' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
            'due_date' => 'nullable|date',
        ]);

        $data['provider_id'] = $request->user()->id;
        $data['status'] = $data['status'] ?? CaseStatus::Open->value;
        $data['stage'] = $data['stage'] ?? CaseStage::Intake->value;
        $data['priority'] = $data['priority'] ?? CasePriority::Medium->value;

        $case = CaseRecord::create($data);

        return new CaseRecordResource($case->load(['client', 'tags']));
    }

    public function update(Request $request, CaseRecord $case)
    {
        $this->authorize('update', $case);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => ['sometimes', 'string'],
            'stage' => ['sometimes', 'string'],
            'priority' => ['sometimes', 'string'],
            'due_date' => 'nullable|date',
        ]);

        $case->update($data);

        return new CaseRecordResource($case->fresh(['client', 'tags']));
    }

    public function destroy(CaseRecord $case)
    {
        $this->authorize('delete', $case);
        $case->delete();
        return response()->noContent();
    }
}
