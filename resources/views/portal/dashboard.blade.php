@extends('layouts.portal')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ $client->first_name }}</h1>
    <p class="mt-1 text-gray-600">Here's an overview of your cases.</p>

    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900">Recent Cases</h2>
        <div class="mt-4 bg-white rounded-xl border border-gray-200 overflow-hidden">
            @forelse ($cases as $case)
                <a href="{{ route('portal.cases.show', $case) }}" class="block p-4 border-b border-gray-100 last:border-0 hover:bg-gray-50">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $case->title }}</p>
                            <p class="text-sm text-gray-500">Updated {{ $case->updated_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800">{{ $case->status->label() }}</span>
                    </div>
                </a>
            @empty
                <p class="p-4 text-gray-500">No cases yet.</p>
            @endforelse
        </div>
    </div>
@endsection
