<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Client Portal') - CaseFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('portal.dashboard') }}" class="text-xl font-bold text-amber-600">CaseFlow</a>
                    <div class="hidden md:flex ml-10 space-x-8">
                        <a href="{{ route('portal.dashboard') }}" class="text-gray-700 hover:text-amber-600 {{ request()->routeIs('portal.dashboard') ? 'text-amber-600 font-semibold' : '' }}">Dashboard</a>
                        <a href="{{ route('portal.cases') }}" class="text-gray-700 hover:text-amber-600 {{ request()->routeIs('portal.cases*') ? 'text-amber-600 font-semibold' : '' }}">My Cases</a>
                        <a href="{{ route('portal.invoices') }}" class="text-gray-700 hover:text-amber-600 {{ request()->routeIs('portal.invoices') ? 'text-amber-600 font-semibold' : '' }}">Invoices</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                    <form action="{{ route('portal.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
