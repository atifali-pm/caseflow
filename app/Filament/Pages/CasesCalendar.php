<?php

namespace App\Filament\Pages;

use App\Models\CaseRecord;
use Carbon\Carbon;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class CasesCalendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Case Management';

    protected static ?string $title = 'Calendar';

    protected static ?string $slug = 'cases/calendar';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.cases-calendar';

    #[Url]
    public ?string $month = null;

    public function mount(): void
    {
        $this->month = $this->month ?? now()->format('Y-m');
    }

    public function previousMonth(): void
    {
        $this->month = Carbon::createFromFormat('Y-m', $this->month)->subMonth()->format('Y-m');
    }

    public function nextMonth(): void
    {
        $this->month = Carbon::createFromFormat('Y-m', $this->month)->addMonth()->format('Y-m');
    }

    public function getCalendarData(): array
    {
        $date = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth();
        $startOfCalendar = $date->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfCalendar = $date->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $cases = CaseRecord::with('client')
            ->whereBetween('due_date', [$startOfCalendar, $endOfCalendar])
            ->get()
            ->groupBy(fn ($case) => $case->due_date->format('Y-m-d'));

        $days = [];
        $current = $startOfCalendar->copy();
        while ($current <= $endOfCalendar) {
            $days[] = [
                'date' => $current->copy(),
                'in_month' => $current->month === $date->month,
                'cases' => $cases->get($current->format('Y-m-d'), collect()),
            ];
            $current->addDay();
        }

        return [
            'month_label' => $date->format('F Y'),
            'days' => $days,
        ];
    }
}
