<x-filament-panels::page>
    @if ($newToken)
        <div class="rounded-xl border border-amber-200 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-700 p-4">
            <p class="font-semibold text-amber-900 dark:text-amber-100">Your new token</p>
            <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">Copy this now, it will not be shown again.</p>
            <code class="block mt-2 p-3 bg-white dark:bg-gray-900 rounded-lg text-xs break-all border border-amber-200 dark:border-amber-700">{{ $newToken }}</code>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Name</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Last Used</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Created</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->getTokens() as $token)
                    <tr class="border-t border-gray-100 dark:border-gray-800">
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $token->name }}</td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                            {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                            {{ $token->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="deleteToken({{ $token->id }})"
                                    wire:confirm="Revoke this token?"
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 text-sm">
                                Revoke
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            No tokens yet. Create one to start using the API.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 bg-gray-50 dark:bg-gray-800 rounded-xl p-4 text-sm text-gray-600 dark:text-gray-300">
        <p class="font-semibold mb-2">Quick start:</p>
        <pre class="bg-white dark:bg-gray-900 p-3 rounded-lg text-xs overflow-x-auto">curl -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  http://caseflow.local:8010/api/cases</pre>
    </div>
</x-filament-panels::page>
