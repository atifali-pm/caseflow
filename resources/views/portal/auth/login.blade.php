<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Login - CaseFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900 text-center">Client Portal</h1>
        <p class="text-sm text-gray-600 text-center mt-2">Sign in to view your cases</p>

        @if ($errors->any())
            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('portal.login') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition">
                Sign In
            </button>
        </form>
    </div>
</body>
</html>
