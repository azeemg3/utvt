<?php

namespace App\Repositories;

use App\Jobs\SendLeadEmail;
use App\Mail\LeadWelcomeEmail;
use App\Models\BusinessSetting;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadReminder;
use App\Models\Lms\LeadConversation;
use App\Models\User;
use App\Notifications\PushNotification;
use App\Repositories\Interfaces\LeadRepositoryInterface;
use App\Services\LeadNotificationService;
use App\Services\LeadReminderNotificationService;
use App\Services\NotificationService;
use App\Services\TwilioServices;
use Auth;
use Carbon\Carbon;
use DB;
use Helpers;
use Illuminate\Database\QueryException;
use Mail;
use Route;

class LeadRepository implements LeadRepositoryInterface
{
    private $twilioServices;
    private $notificationService;
    private $reminderNotificationService;
    private $leadNotificationService;

    public function __construct(
        TwilioServices $twilioServices,
        NotificationService $notificationService,
        LeadReminderNotificationService $reminderNotificationService,
        LeadNotificationService $leadNotificationService
    ) {
        $this->twilioServices = $twilioServices;
        $this->notificationService = $notificationService;
        $this->reminderNotificationService = $reminderNotificationService;
        $this->leadNotificationService = $leadNotificationService;
    }

    public function store($data)
    {
        DB::beginTransaction();
        $mobile = str_replace(' ', '', $data['mobile']);
        $data['mobile'] = $mobile;
        $service_date_from = trim((string) ($data['service_date_from'] ?? ''));
        $service_date_to = trim((string) ($data['service_date_to'] ?? ''));
        $data['service_date_from'] = $service_date_from !== '' ? Helpers::db_date_format($service_date_from) : null;
        $data['service_date_to'] = $service_date_to !== '' ? Helpers::db_date_format($service_date_to) : null;
        $data['services'] = json_encode($data['services'] ?? []);
        if (isset($data['sectors'])) {
            $data['sectors'] = json_encode($data['sectors']);
        }
        if (isset($data['sectorss'])) {
            $data['sectorss'] = json_encode($data['sectorss']);
        }
        if (isset($data['airline'])) {
            $data['airlines'] = json_encode($data['airline']);
        }
        if (empty($data['CTID'])) {
            $data['CTID'] = 0;
        }
        if (empty($data['created_by'])) {
            $configuredId = (int) config('services.website_lead.system_user_id', 0);
            $fallbackId = Auth::id()
                ?: ($configuredId > 0 && User::whereKey($configuredId)->exists() ? $configuredId : null)
                ?: User::query()->orderBy('id')->value('id');
            $data['created_by'] = (int) $fallbackId;
        }

        $type = (string) ($data['type'] ?? '1');
        $isOpenChannel = $this->leadNotificationService->isOpenChannelSource($data['source_id'] ?? null);
        unset($data['type'], $data['airline']);

        // type 1 = create & takeover
        // Facebook / Website = open lead, notify everyone with sound
        // otherwise assign SPO and notify that SPO with sound
        if ($type === '1') {
            $SPO = Auth::user()->id;
            $data['status'] = '2';
            $data['BOXID'] = 1;
            $data['spo'] = $SPO;
        } elseif ($isOpenChannel) {
            $data['status'] = '1';
            $data['BOXID'] = 0;
            $data['spo'] = null;
            $SPO = null;
        } else {
            $data['status'] = '1';
            $data['BOXID'] = 1;
            $SPO = $data['spo'] ?? null;
            $data['spo'] = $SPO;
        }

        try {
            $ret = Lead::create($data);
            if ($ret) {
                $actorId = Auth::id() ?: (int) ($data['created_by'] ?? 0);
                if ($actorId) {
                    $leadAc['LID'] = $ret->id;
                    $leadAc['action_by'] = $actorId;
                    $leadAc['action_status'] = '1';
                    $leadAc['action_time'] = now();
                    LeadActivity::create($leadAc);

                    if ($type === '1') {
                        $leadAc['action_status'] = '2';
                        $leadAc['action_time'] = now();
                        LeadActivity::create($leadAc);
                    }
                }

                if ($type !== '1' && $isOpenChannel) {
                    $ret->loadMissing('source');
                    $this->leadNotificationService->notifyEveryoneNewLead($ret);
                } elseif ($type !== '1' && !empty($SPO)) {
                    $this->leadNotificationService->notifyAssignedSpo($ret, (int) $SPO);
                }

                $con['leadId'] = $ret->id;
                $con['conversation'] = $data['other_details'] ?? '';
                $con['contact_via'] = 'email';
                LeadConversation::create($con);

                $site = BusinessSetting::first();
                $brand = optional($site)->business_name ?: config('app.name');
                $phone = optional($site)->business_phone ?: '';
                $message = "Thank you " . strtoupper($data['contact_name']) . ",\n{$brand} appreciates your interest in our luxury wardrobes, kitchens and interior solutions.\nOur team will contact you shortly."
                    . ($phone ? " For queries call {$phone}." : '');
                $mailData = [
                    'title' => 'Mail from Webappsoft',
                    'body' => 'This is for testing email usign smtp',
                    'mobile' => $data['mobile'],
                    'message' => $message,
                ];
                dispatch(new SendLeadEmail($mailData))->delay(now()->addSeconds(30));
            }
            DB::commit();
            return $ret;
        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function check_lead($mobile_number)
    {
        $mobile_number = str_replace(' ', '', $mobile_number);
        $res = Lead::with('leadSpo')->where("mobile", $mobile_number)->orWhere('id', $mobile_number);
        if ($res->first()) {
            return $res->first();
        }
    }

    public function lead_boxes($status)
    {
        if ($status != 0) {
            $res = Lead::where("status", $status)->count();
        } else {
            if (Auth::user()->role->name == 'Admin' && Route::currentRouteName() != 'lead.my_leads') {
                $res = Lead::all()->count();
            } else {
                $res = Lead::where('spo', Auth::user()->id)
                    ->orWhere('created_by', Auth::user()->id)
                    ->count();
            }
        }
        return Helpers::leadId_fromat($res);
    }

    public function show($id)
    {
        return $lead = Lead::with(['country', 'source', 'createdBy', 'leadSpo', 'reopen_lead_by'])->find($id);
    }

    public function destroy($id)
    {
        LeadActivity::where('LID', $id)->delete();
        return Lead::destroy($id);
    }

    public function takeover_lead($data)
    {
        if (isset($data['id'])) {
            $leadId = $data['id'];
            $lead = Lead::where('id', $leadId)->update(['status' => 2, 'BOXID' => 1, 'spo' => Auth::user()->id]);
            if ($lead) {
                LeadActivity::create([
                    'LID' => $leadId,
                    'action_by' => Auth::user()->id,
                    'action_status' => 2,
                    'action_time' => now(),
                ]);
            }
            $lead = Lead::find($leadId);
            $notiArray = ["title" => 'Lead Takenover', 'body' => 'Lead No:' . $leadId . ' Takenover by ' . Auth::user()->name . ''];
            $creator = $lead?->created_by ? User::find($lead->created_by) : null;
            if ($creator && (int) $creator->id !== (int) Auth::id()) {
                $this->notificationService->send_notification($notiArray, $creator->id);
                $creator->notify(new PushNotification([
                    'message' => $notiArray['body'],
                    'lead_id' => $leadId,
                    'url' => url('lms/lead/' . $leadId),
                    'type' => 'lead_takenover',
                ]));
            }
            $site = BusinessSetting::first();
            $brand = optional($site)->business_name ?: config('app.name');
            $phone = optional($site)->business_phone ?: '';
            $message = 'Dear ' . $lead->contact_name . ' Your sales person is ' . Auth::user()->name
                . '. Email:' . Auth::user()->email . ' Mobile:' . Auth::user()->mobile
                . ($phone ? ' For details call ' . $phone . '.' : '')
                . ' Thanks — ' . $brand . '.';
            $this->notificationService->send_sms4_connect($lead->mobile, $message);
            return redirect('lms/lead/' . $leadId . '')->with('message', 'Lead Takenover Successfully..!!');
        }
    }

    public function lead_conversation($data, $id)
    {
        if ($data->method() == "GET") {
            $ret = LeadConversation::with('user')->where('leadId', $id)->orderBy('id', 'DESC')->get();
            return $ret;
        } else if (isset($data->leadId)) {
            $con['leadId'] = $data->leadId;
            $con['conversation'] = $data->message;
            $con['contact_via'] = $data->contact_via;
            $ret = LeadConversation::create($con);
            if ($ret) {
                Lead::where("id", $data->leadId)->update(['BOXID' => $data->BOXID]);
            }
            if ($ret && !empty($data->reminder_date) && !empty($data->reminder_time)) {
                LeadReminder::where('leadId', $data->leadId)->where('status', 0)->update(['status' => 1]);
                $due = $this->reminderNotificationService->parseDateTime($data->reminder_date, $data->reminder_time);
                if (!$due) {
                    $ret->reminder_due = null;
                    $ret->user;
                    return $ret;
                }
                $rem['leadId'] = $data->leadId;
                $rem['message'] = $data->message;
                $rem['reminder_date'] = $due->format('Y-m-d');
                $rem['reminder_time'] = $due->format('H:i:s');
                $rem['created_by'] = Auth::user()->id;
                $reminder = LeadReminder::create($rem);
                $this->reminderNotificationService->schedule($reminder);
                $ret->reminder_due = $due->toIso8601String();
                $ret->reminder_id = $reminder->id;
                $ret->reminder_body = strip_tags((string) $data->message);
            }
            $ret->user;
            return $ret;
        }
    }

    public function change_status($id, $status)
    {
        if ($status == '4' || $status == '5') {
            $ret = Lead::where('id', $id)->update(['status' => $status, 'BOXID' => '18']);
        } else {
            $ret = Lead::where('id', $id)->update(['status' => $status]);
        }
        if ($ret) {
            $data['LID'] = $id;
            $data['action_status'] = $status;
            $data['action_by'] = Auth::user()->id;
            $data['action_time'] = now();
            LeadActivity::create($data);
            return $ret;
        }
    }

    public function lead_reason($request)
    {
        $leadId = $request->leadId;
        $ret = Lead::where('id', $leadId)->update(['status' => 5]);
        if ($ret) {
            $data['LID'] = $leadId;
            $data['action_status'] = 5;
            $data['comments'] = $request->comment;
            $data['reason_id'] = $request->reason_id;
            $data['action_by'] = Auth::user()->id;
            $data['action_time'] = now();
            LeadActivity::create($data);
            return $ret;
        }
    }

    public function edit($id)
    {
        return Lead::find($id);
    }

    public function update($data, $id)
    {
        DB::beginTransaction();
        $mobile = $data->mobile;
        $service_date_from = Helpers::db_date_format($data->service_date_from);
        $service_date_to = Helpers::db_date_format($data->service_date_to);
        $data['service_date_from'] = $service_date_from;
        $data['service_date_to'] = $service_date_to;
        $data['services'] = json_encode($data->services ?? []);
        $data['sectors'] = json_encode($data->sectors ?? []);
        if (isset($data->sectorss)) {
            $data['sectorss'] = json_encode($data->sectorss);
        }
        if (isset($data->airline)) {
            $data['airlines'] = json_encode($data->airline);
        }
        if (empty($data['CTID'])) {
            $data['CTID'] = 0;
        }
        $SPO = $data['spo'];
        $data['spo'] = $SPO;
        $data = $data->except(['airline']);
        try {
            $ret = Lead::where('id', $id)->update($data);
            DB::commit();
            return $ret;
        } catch (QueryException $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function reopen_lead($id)
    {
        return Lead::find($id);
    }

    public function lead_reopen($data)
    {
        DB::beginTransaction();
        $id = $data->id;
        $service_date_from = Helpers::db_date_format($data->service_date_from);
        $service_date_to = Helpers::db_date_format($data->service_date_to);
        $data['service_date_from'] = $service_date_from;
        $data['service_date_to'] = $service_date_to;
        $data['services'] = json_encode($data->services ?? []);
        $data['sectors'] = json_encode($data->sectors ?? []);
        if (isset($data->sectorss)) {
            $data['sectorss'] = json_encode($data->sectorss);
        }
        if (isset($data->airline)) {
            $data['airlines'] = json_encode($data->airline);
        }
        if (empty($data['CTID'])) {
            $data['CTID'] = 0;
        }
        $SPO = $data['spo'];
        $data['spo'] = $SPO;
        $data['status'] = '1';
        $data['BOXID'] = '0';
        $data['reopen_by'] = Auth::user()->id;
        $data['reopen_at'] = date('Y-m-d h:i:s');
        $data = $data->except(['airline']);
        try {
            $ret = Lead::where('id', $id)->update($data);
            DB::commit();
            return $ret;
        } catch (QueryException $e) {
            DB::rollBack();
            dd($e);
        }
    }
    public function transfer_lead($data)
    {
        $leadId = (int) ($data->leadId ?? $data->input('leadId') ?? 0);
        $spo = (int) ($data->spo ?? $data->input('spo') ?? 0);

        if ($leadId < 1 || $spo < 1) {
            return [
                'success' => false,
                'message' => 'Please select a valid SPO to transfer.',
            ];
        }

        $lead = Lead::find($leadId);
        if (!$lead) {
            return [
                'success' => false,
                'message' => 'Lead not found.',
            ];
        }

        // BOXID 0 = pending lead (from remote leadTrasfertoPendngLead)
        Lead::where('id', $leadId)->update(['spo' => $spo, 'status' => '1', 'BOXID' => 0]);
        LeadActivity::create([
            'LID' => $leadId,
            'action_status' => 1,
            'comments' => 'Lead Transfer',
            'action_by' => Auth::user()->id,
            'action_time' => now(),
        ]);

        $lead->refresh();
        $this->leadNotificationService->notifyLeadTransfer($lead, $spo, Auth::user()->name ?? null);

        $spoUser = User::find($spo);

        return [
            'success' => true,
            'message' => 'Lead transferred successfully. Notification sent to ' . ($spoUser->name ?? 'SPO') . '.',
            'notified' => true,
            'spo_id' => $spo,
            'spo_name' => $spoUser->name ?? '',
            'has_device_token' => !empty($spoUser->device_token ?? null),
        ];
    }

    public function save_reminder($data)
    {
        if (!empty($data->reminder_date) && !empty($data->reminder_time)) {
            LeadReminder::where('leadId', $data->leadId)->where('status', 0)->update(['status' => 1]);
            $due = $this->reminderNotificationService->parseDateTime($data->reminder_date, $data->reminder_time);
            if (!$due) {
                return ['ok' => false];
            }
            $rem['leadId'] = $data->leadId;
            $rem['message'] = $data->message;
            $rem['reminder_date'] = $due->format('Y-m-d');
            $rem['reminder_time'] = $due->format('H:i:s');
            $rem['created_by'] = Auth::user()->id;
            $reminder = LeadReminder::create($rem);
            $this->reminderNotificationService->schedule($reminder);
            return [
                'ok' => true,
                'reminder_id' => $reminder->id,
                'reminder_due' => $due->toIso8601String(),
                'reminder_body' => strip_tags((string) $data->message),
                'lead_id' => $data->leadId,
            ];
        }
        return ['ok' => false];
    }
}
