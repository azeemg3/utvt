<?php

namespace App\Console\Commands;

use App\Services\LeadReminderNotificationService;
use Illuminate\Console\Command;

class SendDueLeadReminders extends Command
{
    protected $signature = 'leads:send-due-reminders';

    protected $description = 'Send Firebase notifications for due lead reminders';

    public function handle(LeadReminderNotificationService $service): int
    {
        $sent = $service->sendDueReminders();
        $this->info('Sent ' . count($sent) . ' lead reminder notification(s).');

        return self::SUCCESS;
    }
}
