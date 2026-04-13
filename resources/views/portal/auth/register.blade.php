<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Registration - CaseFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900 text-center">Complete Your Account</h1>
        <p class="text-sm text-gray-600 text-center mt-2">Welcome, {{ $client->first_name }}. Set your password to access the portal.</p>

        @if ($errors->any())
            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('portal.register.store') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $client->invitation_token }}">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" value="{{ $client->email }}" disabled class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Your Name</label>
                <input type="text" name="name" value="{{ $client->full_name }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition">
                Create Account
            </button>
        </form>
    </div>
</body>
</html>
