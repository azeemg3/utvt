<?php

namespace App\Jobs;

use App\Services\LeadReminderNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LeadReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $reminderId)
    {
    }

    public function handle(LeadReminderNotificationService $service): void
    {
        try {
            $reminder = \App\Models\LeadReminder::find($this->reminderId);
            if (!$reminder) {
                return;
            }
            $service->notifyReminder($reminder);
        } catch (\Throwable $e) {
            Log::error('LeadReminder job failed', [
                'reminder_id' => $this->reminderId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
