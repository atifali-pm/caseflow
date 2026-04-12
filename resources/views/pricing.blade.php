<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - CaseFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900">Simple, transparent pricing</h1>
            <p class="mt-4 text-lg text-gray-600">Choose the plan that fits your practice</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach ($plans as $plan)
                <div class="bg-white rounded-2xl shadow-sm border {{ $plan === \App\Enums\Plan::Pro ? 'border-amber-500 ring-2 ring-amber-500' : 'border-gray-200' }} p-8 flex flex-col">
                    @if ($plan === \App\Enums\Plan::Pro)
                        <span class="bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full self-start mb-4">Most Popular</span>
                    @endif

                    <h3 class="text-xl font-semibold text-gray-900">{{ $plan->label() }}</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $plan->priceLabel() }}</p>

                    <ul class="mt-6 space-y-3 flex-grow">
                        @foreach ($plan->features() as $feature)
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8">
                        @if ($currentPlan === $plan)
                            <span class="block w-full text-center py-3 px-4 rounded-lg bg-gray-100 text-gray-500 font-medium">Current Plan</span>
                        @elseif ($plan->stripePriceId())
                            <form action="{{ route('subscribe', $plan->value) }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-center py-3 px-4 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-medium transition">
                                    Subscribe
                                </button>
                            </form>
                        @else
                            <a href="{{ url('/admin/register') }}" class="block w-full text-center py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium transition">
                                Get Started
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @auth
            @if (auth()->user()->subscribed('default'))
                <div class="text-center mt-8">
                    <a href="{{ route('billing-portal') }}" class="text-amber-600 hover:text-amber-700 font-medium">
                        Manage billing &rarr;
                    </a>
                </div>
            @endif
        @endauth
    </div>
</body>
</html>
