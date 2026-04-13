@extends('layouts.portal')

@section('title', $case->title)

@section('content')
    <div class="flex justify-between items-start">
        <div>
            <a href="{{ route('portal.cases') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to cases</a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $case->title }}</h1>
            <div class="mt-2 flex gap-2">
                <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800">{{ $case->status->label() }}</span>
                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">{{ $case->stage->label() }}</span>
            </div>
        </div>
    </div>

    <div class="mt-6 grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @if ($case->description)
                <div class="bg-white p-6 rounded-xl border border-gray-200">
                    <h2 class="font-semibold text-gray-900">Description</h2>
                    <p class="mt-2 text-gray-600">{{ $case->description }}</p>
                </div>
            @endif

            <div class="bg-white p-6 rounded-xl border border-gray-200">
                <h2 class="font-semibold text-gray-900">Milestones</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($case->milestones as $milestone)
                        <div class="flex items-start gap-3">
                            @if ($milestone->completed_at)
                                <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center mt-0.5">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </div>
                            @else
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 mt-0.5"></div>
                            @endif
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $milestone->title }}</p>
                                @if ($milestone->description)
                                    <p class="text-sm text-gray-500">{{ $milestone->description }}</p>
                                @endif
                                @if ($milestone->due_date)
                                    <p class="text-xs text-gray-400 mt-1">Due {{ $milestone->due_date->format('M j, Y') }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No milestones yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-200">
                <h2 class="font-semibold text-gray-900">Messages</h2>
                <div class="mt-4">
                    @livewire('portal.case-messages', ['case' => $case])
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200">
                <h2 class="font-semibold text-gray-900">Documents</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($case->documents as $doc)
                        <div class="text-sm">
                            <p class="font-medium text-gray-900">{{ $doc->filename }}</p>
                            <p class="text-xs text-gray-500">{{ $doc->size_for_humans }} · {{ $doc->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No documents yet.</p>
                    @endforelse
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    @livewire('portal.document-upload', ['case' => $case])
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-200">
                <h2 class="font-semibold text-gray-900">Details</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div>
                        <dt class="text-gray-500">Opened</dt>
                        <dd class="text-gray-900">{{ $case->opened_at->format('M j, Y') }}</dd>
                    </div>
                    @if ($case->due_date)
                        <div>
                            <dt class="text-gray-500">Due Date</dt>
                            <dd class="text-gray-900">{{ $case->due_date->format('M j, Y') }}</dd>
                        </div>
                    @endif
                    @if ($case->closed_at)
                        <div>
                            <dt class="text-gray-500">Closed</dt>
                            <dd class="text-gray-900">{{ $case->closed_at->format('M j, Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
@endsection
