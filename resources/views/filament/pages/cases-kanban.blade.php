<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($this->getColumns() as $column)
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $column['stage']->label() }}</h3>
                    <span class="text-xs px-2 py-1 rounded-full bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                        {{ $column['cases']->count() }}
                    </span>
                </div>
                <div class="space-y-2 min-h-[200px]">
                    @forelse ($column['cases'] as $case)
                        <a href="{{ route('filament.admin.resources.cases.edit', $case) }}"
                           class="block bg-white dark:bg-gray-900 rounded-lg p-3 shadow-sm hover:shadow-md transition border border-gray-200 dark:border-gray-700">
                            <p class="font-medium text-sm text-gray-900 dark:text-gray-100 mb-1">{{ \Illuminate\Support\Str::limit($case->title, 50) }}</p>
                            @if ($case->client)
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $case->client->full_name }}</p>
                            @endif
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    @if ($case->priority?->value === 'urgent') bg-red-100 text-red-800
                                    @elseif ($case->priority?->value === 'high') bg-orange-100 text-orange-800
                                    @elseif ($case->priority?->value === 'medium') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $case->priority?->label() ?? 'Medium' }}
                                </span>
                                @if ($case->due_date)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $case->due_date->format('M j') }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-gray-400 dark:text-gray-500 text-center py-4">No cases</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
