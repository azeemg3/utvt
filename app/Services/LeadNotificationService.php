<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Lms\SourceQuery;
use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeadNotificationService
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    public function isOpenChannelSource($sourceId): bool
    {
        if (!$sourceId) {
            return false;
        }

        $source = SourceQuery::find($sourceId);
        if (!$source || empty($source->name)) {
            return false;
        }

        $name = strtolower(trim($source->name));
        return str_contains($name, 'facebook')
            || str_contains($name, 'website')
            || str_contains($name, 'free quote');
    }

    public function notifyAssignedSpo(Lead $lead, int $spoId): void
    {
        if ($spoId < 1) {
            return;
        }

        $user = User::find($spoId);
        if (!$user) {
            Log::warning('Lead assign notify skipped: SPO not found', ['spo_id' => $spoId, 'lead_id' => $lead->id]);
            return;
        }

        $leadUrl = url('lms/lead/' . $lead->id);
        $title = 'New Lead Assigned';
        $body = 'Lead #' . $lead->id . ' (' . ($lead->contact_name ?: 'New Lead') . ') assigned to you.';

        $this->pushToUser($user, $title, $body, $leadUrl, [
            'type' => 'lead_assigned',
            'lead_id' => (string) $lead->id,
            'play_sound' => '1',
        ]);
    }

    public function notifyEveryoneNewLead(Lead $lead, ?string $sourceName = null): void
    {
        $sourceLabel = $sourceName ?: optional($lead->source)->name ?: 'Online';
        $leadUrl = url('lms/all_leads');
        $title = 'New ' . $sourceLabel . ' Lead';
        $body = 'Lead #' . $lead->id . ' from ' . $sourceLabel . ' — takeover to start working.';

        // Only exclude the logged-in creator. Do NOT exclude website system_user
        // (created_by) — otherwise Admin never hears website lead alerts.
        $excludeId = (int) (Auth::id() ?: 0);
        $users = User::query()
            ->whereNotNull('id')
            ->when($excludeId > 0, fn ($q) => $q->where('id', '!=', $excludeId))
            ->get();

        foreach ($users as $user) {
            $this->pushToUser($user, $title, $body, $leadUrl, [
                'type' => 'lead_available',
                'lead_id' => (string) $lead->id,
                'source' => (string) $sourceLabel,
                'play_sound' => '1',
            ]);
        }
    }

    public function notifyLeadTransfer(Lead $lead, int $spoId, ?string $byName = null): void
    {
        if ($spoId < 1) {
            Log::warning('Lead transfer notify skipped: invalid SPO', ['spo_id' => $spoId, 'lead_id' => $lead->id]);
            return;
        }

        $user = User::find($spoId);
        if (!$user) {
            Log::warning('Lead transfer notify skipped: SPO not found', ['spo_id' => $spoId, 'lead_id' => $lead->id]);
            return;
        }

        $by = $byName ?: (Auth::user()->name ?? 'Admin');
        $leadUrl = url('lms/lead/' . $lead->id);
        $title = 'Lead Transferred To You';
        $body = 'Lead #' . $lead->id . ' (' . ($lead->contact_name ?: 'Lead') . ') transferred to you by ' . $by . '.';

        // Always create a fresh unread DB notification for recipient B
        $this->pushToUser($user, $title, $body, $leadUrl, [
            'type' => 'lead_transfer',
            'lead_id' => (string) $lead->id,
            'play_sound' => '1',
        ]);

        Log::info('Lead transfer notification sent', [
            'lead_id' => $lead->id,
            'spo_id' => $spoId,
            'spo_name' => $user->name,
            'has_device_token' => !empty($user->device_token),
            'url' => $leadUrl,
        ]);
    }

    private function pushToUser(User $user, string $title, string $body, string $url, array $extra = []): void
    {
        try {
            $user->notify(new PushNotification([
                'message' => $body,
                'lead_id' => $extra['lead_id'] ?? null,
                'url' => $url,
                'type' => $extra['type'] ?? 'lead',
                'play_sound' => $extra['play_sound'] ?? '1',
            ]));
        } catch (\Throwable $e) {
            Log::warning('Lead DB notification failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        $this->notificationService->send_notification([
            'title' => $title,
            'body' => $body,
            'click_action' => $url,
            'data' => array_merge([
                'title' => $title,
                'body' => $body,
                'url' => $url,
                'click_action' => $url,
                'play_sound' => '1',
            ], $extra),
        ], $user->id);
    }
}
