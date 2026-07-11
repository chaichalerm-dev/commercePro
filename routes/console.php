<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
| Run by a single cron entry in production:
|   * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
*/

// Keep the audit trail bounded — 90 days is plenty for a demo shop.
Schedule::call(function (): void {
    ActivityLog::query()->where('created_at', '<', now()->subDays(90))->delete();
})->daily()->name('prune-activity-logs');

// Clear out failed queue jobs older than a week.
Schedule::command('queue:prune-failed --hours=168')->weekly();
