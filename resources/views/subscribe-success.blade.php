<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Successful - CaseFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Subscription Activated</h1>
        <p class="mt-2 text-gray-600">Your plan is now active. Head to your dashboard to get started.</p>
        <a href="{{ url('/admin') }}" class="inline-block mt-6 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition">
            Go to Dashboard
        </a>
    </div>
</body>
</html>
