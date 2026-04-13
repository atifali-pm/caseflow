@extends('layouts.portal')

@section('title', 'My Cases')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900">My Cases</h1>

    <div class="mt-6 bg-white rounded-xl border border-gray-200 overflow-hidden">
        @forelse ($cases as $case)
            <a href="{{ route('portal.cases.show', $case) }}" class="block p-4 border-b border-gray-100 last:border-0 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-900">{{ $case->title }}</p>
                        <p class="mt-1 text-sm text-gray-500">Opened {{ $case->opened_at->format('M j, Y') }}</p>
                        @if ($case->due_date)
                            <p class="text-sm text-gray-500">Due {{ $case->due_date->format('M j, Y') }}</p>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2 items-end">
                        <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800">{{ $case->status->label() }}</span>
                        <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">{{ $case->stage->label() }}</span>
                    </div>
                </div>
            </a>
        @empty
            <p class="p-4 text-gray-500">No cases yet.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $cases->links() }}</div>
@endsection
