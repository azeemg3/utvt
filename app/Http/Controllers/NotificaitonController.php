<?php

namespace App\Http\Controllers;

use App\Services\LeadReminderNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Helpers;
use Notification;

class NotificaitonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LeadReminderNotificationService $reminderService)
    {
        // Process due reminders for the logged-in user (realtime while CRM is open)
        $due_notifications = $reminderService->sendDueReminders(Auth::id());
        $sent_reminders = count($due_notifications);

        $reminder_count = Helpers::fetch_reminder_notification();
        $count_notify = auth()->user()->unreadNotifications()->count();

        $notify_data = auth()->user()
            ->unreadNotifications()
            ->where('type', 'App\Notifications\PushNotification')
            ->latest()
            ->take(20)
            ->get();

        // Recent lead alerts for sound/popup (transfer, assign, facebook/website)
        $lead_alerts = [];
        $recent = auth()->user()
            ->unreadNotifications()
            ->where('type', 'App\Notifications\PushNotification')
            ->where('created_at', '>=', Carbon::now()->subMinutes(60))
            ->latest()
            ->take(15)
            ->get();

        foreach ($recent as $row) {
            $payload = is_array($row->data) ? $row->data : (json_decode((string) $row->data, true) ?: []);
            $type = $payload['type'] ?? '';
            $playSound = ($payload['play_sound'] ?? null) == '1' || ($payload['play_sound'] ?? null) === 1;
            if (!in_array($type, ['lead_transfer', 'lead_assigned', 'lead_available'], true) && !$playSound) {
                continue;
            }

            $lead_alerts[] = [
                'id' => $row->id,
                'title' => $type === 'lead_transfer'
                    ? 'Lead Transferred To You'
                    : ($type === 'lead_available' ? 'New Online Lead' : 'New Lead Assigned'),
                'body' => $payload['data'] ?? 'You have a new lead notification.',
                'url' => $payload['url'] ?? url('lms/all_leads'),
                'type' => $type ?: 'lead',
                'lead_id' => $payload['lead_id'] ?? null,
                'play_sound' => '1',
                'created_at' => optional($row->created_at)->toIso8601String(),
            ];
        }

        return response()->json(compact(
            'count_notify',
            'notify_data',
            'reminder_count',
            'sent_reminders',
            'due_notifications',
            'lead_alerts'
        ));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    /**
     * Mark one or many notifications as read (stops repeated alerts).
     */
    public function markRead(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids)) {
            $ids = array_filter([$ids, $request->input('id')]);
        }
        $ids = array_values(array_filter(array_map('strval', $ids)));

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No notification id provided'], 422);
        }

        $query = auth()->user()->unreadNotifications()->whereIn('id', $ids);
        $count = $query->count();
        $query->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'marked' => $count,
            'unread' => auth()->user()->unreadNotifications()->count(),
        ]);
    }

    public function all_notifications(Request $request)
    {
        $notify_data = auth()->user()->unreadNotifications()->where('type', 'App\Notifications\PushNotification')->get();
        $notify_data->markAsRead();
        return view('all-notifications', compact('notify_data'));
    }
}
