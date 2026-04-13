@extends('layouts.portal')

@section('title', 'Invoices')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
    <p class="mt-2 text-gray-600">Your billing history will appear here.</p>

    <div class="mt-6 bg-white rounded-xl border border-gray-200 p-8 text-center">
        <p class="text-gray-500">No invoices available yet.</p>
    </div>
@endsection
