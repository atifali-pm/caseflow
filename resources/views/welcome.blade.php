<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CaseFlow — Case management for solo practices and small firms</title>
    <meta name="description" content="Run your practice from one panel. Cases, clients, time tracking, invoicing, and a polished client portal — all backed by Stripe.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: { 50: '#fffbeb', 100: '#fef3c7', 500: '#f59e0b', 600: '#d97706', 700: '#b45309', 900: '#78350f' } }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(245, 158, 11, 0.15), transparent); }
        .feature-card { transition: all 0.2s; }
        .feature-card:hover { transform: translateY(-2px); border-color: #f59e0b; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">

    <nav class="border-b border-gray-100 sticky top-0 bg-white/80 backdrop-blur-md z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <span class="font-bold text-xl">CaseFlow</span>
            </div>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                <a href="#features" class="hover:text-gray-900">Features</a>
                <a href="#how-it-works" class="hover:text-gray-900">How it works</a>
                <a href="{{ route('pricing') }}" class="hover:text-gray-900">Pricing</a>
                <a href="https://github.com/atifali-pm/caseflow" class="hover:text-gray-900">GitHub</a>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/admin/login') }}" class="hidden sm:block text-sm font-medium text-gray-600 hover:text-gray-900">Sign in</a>
                <a href="{{ url('/admin/register') }}" class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                    Get started
                </a>
            </div>
        </div>
    </nav>

    <section class="gradient-bg pt-20 pb-16">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 border border-brand-100 text-brand-700 text-xs font-semibold mb-6">
                <span class="w-1.5 h-1.5 bg-brand-500 rounded-full"></span>
                Multi-tenant SaaS, Stripe-ready
            </div>
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-gray-900 leading-[1.05]">
                Run your practice<br>from one panel.
            </h1>
            <p class="mt-6 text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                CaseFlow is case management for solo practices and small firms. Track cases, log time, send invoices, message clients, and bill on Stripe. All in one place.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url('/admin/register') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-3 rounded-lg transition shadow-sm">
                    Start free, no credit card
                </a>
                <a href="{{ route('pricing') }}" class="bg-white hover:bg-gray-50 text-gray-900 font-semibold px-6 py-3 rounded-lg border border-gray-200 transition">
                    See pricing
                </a>
            </div>
            <p class="mt-6 text-sm text-gray-500">
                Free tier: 5 cases. No credit card. Upgrade when you need more.
            </p>

            <div class="mt-12 inline-flex flex-wrap gap-2 justify-center items-center">
                <span class="text-xs font-medium text-gray-500">Try the demo:</span>
                <code class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-700">sarah@caseflow.test</code>
                <span class="text-xs text-gray-400">/</span>
                <code class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-700">password</code>
            </div>
        </div>
    </section>

    <section class="border-y border-gray-100 bg-gray-50/50">
        <div class="max-w-5xl mx-auto px-6 py-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-center md:text-left">
            <div>
                <div class="text-3xl font-bold text-gray-900">14+</div>
                <div class="text-sm text-gray-500 mt-1">Models</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900">30+</div>
                <div class="text-sm text-gray-500 mt-1">Filament pages</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900">REST</div>
                <div class="text-sm text-gray-500 mt-1">API + webhooks</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900">$0</div>
                <div class="text-sm text-gray-500 mt-1">Free tier</div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-4xl font-bold tracking-tight">Everything you need, nothing you don't</h2>
                <p class="mt-4 text-gray-600 text-lg">Stop juggling Trello, Google Drive, and a spreadsheet. CaseFlow has the features you'd actually use, built into one app.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                $features = [
                    ['icon' => 'briefcase', 'title' => 'Case management', 'body' => 'Track cases through Intake, Active, Review, and Closed. Add milestones, set priorities, attach documents.'],
                    ['icon' => 'users', 'title' => 'Client CRM', 'body' => 'Full client records with contact details, notes, and case history. Invite clients to a self-serve portal.'],
                    ['icon' => 'check-circle', 'title' => 'Tasks and milestones', 'body' => 'Assign tasks with due dates and statuses. Sortable milestone timelines per case.'],
                    ['icon' => 'clock', 'title' => 'Time tracking', 'body' => 'Log billable hours with hourly rate snapshots. Roll them into invoices in one click.'],
                    ['icon' => 'document-text', 'title' => 'Invoicing and PDFs', 'body' => 'Auto-numbered invoices with line items. Download as professional PDFs. Mark sent and paid.'],
                    ['icon' => 'credit-card', 'title' => 'Stripe billing', 'body' => 'Subscribe to Pro or Enterprise via Stripe Checkout. Manage your plan in the customer portal.'],
                    ['icon' => 'chat-bubble', 'title' => 'Client portal', 'body' => 'Clients log into a separate Livewire portal to view cases, upload docs, and message you.'],
                    ['icon' => 'bolt', 'title' => 'API and webhooks', 'body' => 'REST API with Sanctum tokens. HMAC-signed webhooks for case.created, invoice.paid, and more.'],
                    ['icon' => 'chart-bar', 'title' => 'Reports and search', 'body' => 'Revenue charts, case stage breakdown, time logged this week. Cmd+K global search.'],
                ];
                @endphp

                @foreach ($features as $feature)
                    <div class="feature-card border border-gray-200 rounded-xl p-6 bg-white">
                        <div class="w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                @switch($feature['icon'])
                                    @case('briefcase') <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/> @break
                                    @case('users') <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/> @break
                                    @case('check-circle') <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/> @break
                                    @case('clock') <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/> @break
                                    @case('document-text') <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/> @break
                                    @case('credit-card') <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/> @break
                                    @case('chat-bubble') <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/> @break
                                    @case('bolt') <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/> @break
                                    @case('chart-bar') <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/> @break
                                @endswitch
                            </svg>
                        </div>
                        <h3 class="font-semibold text-lg text-gray-900">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $feature['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-24 bg-gray-50">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-4xl font-bold tracking-tight">How it works</h2>
                <p class="mt-4 text-gray-600 text-lg">Three sides of the same app, sharing one Postgres database, three very different experiences.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 border border-gray-200">
                    <div class="text-xs font-bold text-brand-600 mb-2">FOR PROVIDERS</div>
                    <h3 class="text-xl font-semibold mb-3">Filament admin panel</h3>
                    <p class="text-sm text-gray-600 mb-4">Sign up, create cases and clients, log time, send invoices. Multi-tenant by default. You only see your own data.</p>
                    <a href="{{ url('/admin/login') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">Open admin →</a>
                </div>

                <div class="bg-white rounded-xl p-6 border border-gray-200">
                    <div class="text-xs font-bold text-brand-600 mb-2">FOR CLIENTS</div>
                    <h3 class="text-xl font-semibold mb-3">Livewire portal</h3>
                    <p class="text-sm text-gray-600 mb-4">Clients receive an invitation email, set a password, and log into a clean portal. They see only their cases.</p>
                    <a href="{{ url('/portal/login') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">Open portal →</a>
                </div>

                <div class="bg-white rounded-xl p-6 border border-gray-200">
                    <div class="text-xs font-bold text-brand-600 mb-2">FOR DEVELOPERS</div>
                    <h3 class="text-xl font-semibold mb-3">REST API and webhooks</h3>
                    <p class="text-sm text-gray-600 mb-4">Sanctum bearer tokens. CRUD endpoints for cases and clients. HMAC-signed outbound webhooks for integrations.</p>
                    <a href="https://github.com/atifali-pm/caseflow" class="text-sm font-semibold text-brand-600 hover:text-brand-700">View on GitHub →</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-4xl font-bold tracking-tight">Simple, fair pricing</h2>
                <p class="mt-4 text-gray-600 text-lg">Start free. Pay only when you outgrow the limit.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-4 max-w-4xl mx-auto">
                <div class="border border-gray-200 rounded-xl p-6 bg-white">
                    <div class="font-semibold text-gray-900">Free</div>
                    <div class="mt-2 text-3xl font-bold">$0</div>
                    <div class="text-sm text-gray-500">Up to 5 cases</div>
                </div>
                <div class="border-2 border-brand-500 rounded-xl p-6 bg-white relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-brand-500 text-white text-xs font-bold px-3 py-1 rounded-full">POPULAR</div>
                    <div class="font-semibold text-gray-900">Pro</div>
                    <div class="mt-2 text-3xl font-bold">$29<span class="text-sm font-normal text-gray-500">/mo</span></div>
                    <div class="text-sm text-gray-500">Up to 50 cases</div>
                </div>
                <div class="border border-gray-200 rounded-xl p-6 bg-white">
                    <div class="font-semibold text-gray-900">Enterprise</div>
                    <div class="mt-2 text-3xl font-bold">$99<span class="text-sm font-normal text-gray-500">/mo</span></div>
                    <div class="text-sm text-gray-500">Unlimited cases</div>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('pricing') }}" class="text-brand-600 hover:text-brand-700 font-semibold">Compare full plans →</a>
            </div>
        </div>
    </section>

    <section class="py-16 border-t border-gray-100">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-6">Built with modern tools</p>
            <div class="flex flex-wrap justify-center gap-x-10 gap-y-4 text-gray-400 font-semibold">
                <span>Laravel 12</span>
                <span>Filament 3</span>
                <span>Livewire 3</span>
                <span>PostgreSQL 16</span>
                <span>Stripe Cashier</span>
                <span>Sanctum</span>
                <span>Tailwind CSS</span>
                <span>Docker</span>
            </div>
        </div>
    </section>

    <section class="py-24 bg-gray-900 text-white">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-bold tracking-tight">Ready to ditch the spreadsheet?</h2>
            <p class="mt-6 text-lg text-gray-300">Sign up free. Move your first 5 cases over today. Upgrade only if you need more.</p>
            <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url('/admin/register') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-3 rounded-lg transition">
                    Create your account
                </a>
                <a href="{{ url('/admin/login') }}" class="bg-gray-800 hover:bg-gray-700 text-white font-semibold px-6 py-3 rounded-lg transition border border-gray-700">
                    Sign in
                </a>
            </div>
        </div>
    </section>

    <footer class="border-t border-gray-100 py-12">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 bg-brand-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <span class="font-bold">CaseFlow</span>
                </div>
                <p class="text-sm text-gray-500">Case management for solo practices and small firms.</p>
            </div>
            <div>
                <div class="text-xs font-bold text-gray-500 uppercase mb-3">Product</div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#features" class="hover:text-gray-900">Features</a></li>
                    <li><a href="{{ route('pricing') }}" class="hover:text-gray-900">Pricing</a></li>
                    <li><a href="{{ url('/admin/register') }}" class="hover:text-gray-900">Sign up</a></li>
                </ul>
            </div>
            <div>
                <div class="text-xs font-bold text-gray-500 uppercase mb-3">For users</div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="{{ url('/admin/login') }}" class="hover:text-gray-900">Provider login</a></li>
                    <li><a href="{{ url('/portal/login') }}" class="hover:text-gray-900">Client portal</a></li>
                </ul>
            </div>
            <div>
                <div class="text-xs font-bold text-gray-500 uppercase mb-3">Developers</div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="https://github.com/atifali-pm/caseflow" class="hover:text-gray-900">GitHub</a></li>
                    <li><a href="https://github.com/atifali-pm/caseflow#api" class="hover:text-gray-900">REST API</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-6 mt-8 pt-8 border-t border-gray-100 text-xs text-gray-500">
            CaseFlow is open source on GitHub. Built as a portfolio piece by <a href="https://github.com/atifali-pm" class="text-gray-700 hover:text-gray-900 font-medium">Atif Ali</a>.
        </div>
    </footer>

</body>
</html>
