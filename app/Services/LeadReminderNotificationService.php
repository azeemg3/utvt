<?php

namespace App\Services;

use App\Jobs\LeadReminder as LeadReminderJob;
use App\Models\LeadReminder;
use App\Models\User;
use App\Notifications\PushNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class LeadReminderNotificationService
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    /**
     * Schedule a Firebase push for when the reminder is due.
     */
    public function schedule(LeadReminder $reminder): void
    {
        $dueAt = $this->dueAt($reminder);

        if ($dueAt->isPast() || $dueAt->diffInSeconds(Carbon::now()) < 5) {
            $this->notifyReminder($reminder);
            return;
        }

        LeadReminderJob::dispatch($reminder->id)->delay($dueAt);
    }

    /**
     * Find and notify all due reminders (optionally limited to one user).
     *
     * @return array<int, array<string, mixed>>
     */
    public function sendDueReminders(?int $userId = null): array
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $query = LeadReminder::query()
            ->with('lead')
            ->where('status', 0)
            ->whereRaw("CONCAT(reminder_date, ' ', reminder_time) <= ?", [$now]);

        if (Schema::hasColumn('lead_reminders', 'notified_at')) {
            $query->whereNull('notified_at');
        }

        if ($userId) {
            $query->where('created_by', $userId);
        }

        $sent = [];
        foreach ($query->get() as $reminder) {
            $payload = $this->notifyReminder($reminder);
            if ($payload) {
                $sent[] = $payload;
            }
        }

        return $sent;
    }

    /**
     * Send realtime Firebase + in-app notification for a reminder.
     */
    public function notifyReminder(LeadReminder $reminder): ?array
    {
        $reminder->refresh();

        if ((int) $reminder->status !== 0) {
            return null;
        }

        if (Schema::hasColumn('lead_reminders', 'notified_at') && $reminder->notified_at) {
            return null;
        }

        $reminder->loadMissing('lead');
        $lead = $reminder->lead;
        $leadId = $reminder->leadId;
        $contactName = $lead->contact_name ?? ('Lead #' . $leadId);
        $message = trim((string) ($reminder->message ?: 'You have a follow-up reminder.'));
        $message = strip_tags($message);
        if (strlen($message) > 120) {
            $message = substr($message, 0, 117) . '...';
        }

        $title = 'Lead Reminder Due';
        $body = "Lead #{$leadId} ({$contactName}): {$message}";
        $leadUrl = url('lms/lead/' . $leadId);

        $recipientId = (int) $reminder->created_by;
        if ($recipientId < 1) {
            Log::warning('Lead reminder has no creator', ['reminder_id' => $reminder->id]);
            return null;
        }

        $user = User::find($recipientId);
        if (!$user) {
            return null;
        }

        try {
            $user->notify(new PushNotification([
                'message' => $body,
                'lead_id' => $leadId,
                'url' => $leadUrl,
            ]));
        } catch (\Throwable $e) {
            Log::warning('Lead reminder DB notification failed', [
                'reminder_id' => $reminder->id,
                'user_id' => $recipientId,
                'error' => $e->getMessage(),
            ]);
        }

        $this->notificationService->send_notification([
            'title' => $title,
            'body' => $body,
            'click_action' => $leadUrl,
            'data' => [
                'lead_id' => (string) $leadId,
                'reminder_id' => (string) $reminder->id,
                'url' => $leadUrl,
                'type' => 'lead_reminder',
            ],
        ], $recipientId);

        if (Schema::hasColumn('lead_reminders', 'notified_at')) {
            $reminder->notified_at = Carbon::now();
            $reminder->save();
        }

        return [
            'title' => $title,
            'body' => $body,
            'url' => $leadUrl,
            'reminder_id' => $reminder->id,
            'lead_id' => $leadId,
        ];
    }

    public function dueAt(LeadReminder $reminder): Carbon
    {
        $date = $reminder->getRawOriginal('reminder_date') ?: ($reminder->getAttributes()['reminder_date'] ?? null);
        $time = $reminder->getRawOriginal('reminder_time') ?: ($reminder->getAttributes()['reminder_time'] ?? null);

        return $this->parseDateTime($date, $time) ?: Carbon::now();
    }

    public function parseDateTime(?string $date, ?string $time): ?Carbon
    {
        if ($date === null || $date === '' || $time === null || $time === '') {
            return null;
        }

        $date = trim($date);
        $time = trim($time);
        if (strlen($time) === 5) {
            $time .= ':00';
        }

        $tz = config('app.timezone', 'Asia/Karachi');
        $value = $date . ' ' . $time;
        $formats = ['d-m-Y H:i:s', 'Y-m-d H:i:s', 'd/m/Y H:i:s', 'm/d/Y H:i:s'];

        foreach ($formats as $format) {
            try {
                $dt = Carbon::createFromFormat($format, $value, $tz);
                if ($dt instanceof Carbon) {
                    return $dt;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        try {
            return Carbon::parse($value, $tz);
        } catch (\Throwable $e) {
            Log::warning('Could not parse reminder datetime', [
                'date' => $date,
                'time' => $time,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
