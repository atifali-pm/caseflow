<x-filament-panels::page>
    @php $data = $this->getCalendarData(); @endphp

    <div class="flex items-center justify-between mb-4">
        <button wire:click="previousMonth" class="px-3 py-1 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
            ← Previous
        </button>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $data['month_label'] }}</h2>
        <button wire:click="nextMonth" class="px-3 py-1 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
            Next →
        </button>
    </div>

    <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700 rounded-xl overflow-hidden">
        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="bg-gray-100 dark:bg-gray-800 p-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300">
                {{ $day }}
            </div>
        @endforeach

        @foreach ($data['days'] as $day)
            <div class="bg-white dark:bg-gray-900 p-2 min-h-[100px] {{ ! $day['in_month'] ? 'opacity-40' : '' }}">
                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $day['date']->format('j') }}</p>
                <div class="space-y-1">
                    @foreach ($day['cases']->take(3) as $case)
                        <a href="{{ route('filament.admin.resources.cases.edit', $case) }}"
                           class="block text-xs p-1 rounded
                           @if ($case->priority?->value === 'urgent') bg-red-100 text-red-800
                           @elseif ($case->priority?->value === 'high') bg-orange-100 text-orange-800
                           @else bg-amber-100 text-amber-800 @endif
                           hover:opacity-80 truncate">
                            {{ \Illuminate\Support\Str::limit($case->title, 25) }}
                        </a>
                    @endforeach
                    @if ($day['cases']->count() > 3)
                        <p class="text-xs text-gray-500">+{{ $day['cases']->count() - 3 }} more</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
