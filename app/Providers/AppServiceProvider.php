<?php

namespace App\Providers;

use App\Models\CaseRecord;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Task;
use App\Observers\CaseRecordObserver;
use App\Observers\InvoiceObserver;
use App\Observers\MessageObserver;
use App\Observers\TaskObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        CaseRecord::observe(CaseRecordObserver::class);
        Message::observe(MessageObserver::class);
        Task::observe(TaskObserver::class);
        Invoice::observe(InvoiceObserver::class);
    }
}
