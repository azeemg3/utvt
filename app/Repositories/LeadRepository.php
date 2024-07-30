<?php

namespace App\Repositories;

use App\Jobs\SendLeadEmail;
use App\Mail\LeadWelcomeEmail;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadReminder;
use App\Models\Lms\LeadConversation;
use App\Models\User;
use App\Notifications\PushNotification;
use App\Repositories\Interfaces\LeadRepositoryInterface;
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
    public function __construct(TwilioServices $twilioServices, NotificationService $notificationService)
    {
        $this->twilioServices = $twilioServices;
        $this->notificationService=$notificationService;
    }
    public function store($data)
    {
        DB::beginTransaction();
        $mobile=str_replace(' ','',$data['mobile']);
        $service_date_from=Helpers::db_date_format($data['service_date_from']);
        $service_date_to=Helpers::db_date_format($data['service_date_to']);
        $data['service_date_from']=$service_date_from;
        $data['service_date_to']=$service_date_to;
        $data['services'] = json_encode($data['services']);
        if(isset($data['sectors'])){
            $data['sectors'] = json_encode($data['sectors']);
        }
        if(isset($data['sectorss'])){
            $data['sectorss'] = json_encode($data['sectorss']);
        }
        if(isset($data['airline'])){
            $data['airlines'] = json_encode($data['airline']);
        }
        if ($data['type'] == 1) {
            $SPO = Auth::user()->id;
            $data['status'] = '2';
        } else {
            $data['status'] = '1';
            $SPO = $data['spo'];
        }
        $data['spo'] = $SPO;
        try {
            $ret = Lead::create($data);
            if ($ret) {
                //create lead activity
                $leadAc['LID'] = $ret->id;
                $leadAc['action_by'] = Auth::user()->id;
                $leadAc['action_status'] = '1';
                if ($data['type'] == '2') {
                    $leadAc['action_by'] = Auth::user()->id;
                    $leadAc['action_status'] = '1';
                    LeadActivity::create($leadAc);
                    $notiArray=["title"=>'New Lead','body'=>'You have Assigned New Lead'];
                    $this->notificationService->send_notification($notiArray,$SPO);
                    User::find($SPO)->notify(new PushNotification(['message'=>'Lead Assigned You']));
                }else{
                    LeadActivity::create($leadAc);
                    $leadAc['action_status'] = '2';
                    LeadActivity::create($leadAc);
                }
                $con['leadId'] = $ret->id;
                $con['conversation'] = $data['other_details'];
                $con['contact_via'] ='phone';
                $ret = LeadConversation::create($con);
                $message="Thanks You ".strtoupper($data['contact_name']).",\nTour Vision Travel thankfull and appreciates your support and trust on us.\nYou can be confident that we are committed to your satisfaction. For further details & query please do'nt hesitate to call 03111381888 or visit www.toursvision.com";
                $mailData = [
                    'title' => 'Mail from Webappfix',
                    'body' => 'This is for testing email usign smtp',
                    'mobile' =>$data['mobile'],
                    'message' =>$message,
                ];
                dispatch(new SendLeadEmail($mailData))->delay(now()->addSeconds(30));

            }
            DB::commit();
            return $ret;
        } catch (QueryException $e) {
            DB::rollBack();
            dd($e);
        }
    }
    public function check_lead($mobile_number)
    {
        $mobile_number=str_replace(' ','',$mobile_number);
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
            if(Auth::user()->role->name== 'Admin' && Route::currentRouteName()!='lead.my_leads'){
                $res = Lead::all()->count();
            }else{
                $res = Lead::where('spo', Auth::user()->id)
                ->orWhere('created_by', Auth::user()->id)
                ->count();
            }
        }
        return Helpers::leadId_fromat($res);
    }
    public function show($id)
    {
        return $lead = Lead::with(['country', 'source', 'createdBy', 'leadSpo','reopen_lead_by'])->find($id);
    }
    public function destroy($id)
    {
        LeadActivity::where('LID',$id)->delete();
        return Lead::destroy($id);
    }
    public function takeover_lead($data)
    {
        if (isset($data['id'])) {
            $leadId = $data['id'];
            $lead = Lead::where('id', $leadId)->update(['status' => 2,'BOXID' => 1, 'spo' => Auth::user()->id]);
            if ($lead) {
                LeadActivity::create(['LID' => $leadId, 'action_by' => Auth::user()->id, 'action_status' => 2]);
            }
            $lead=Lead::find($leadId);
            $notiArray=["title"=>'Lead Takenover','body'=>'Lead No:'.$leadId.' Takenover by '.Auth::user()->name.''];
            $this->notificationService->send_notification($notiArray,$lead->created_by);
            User::find($lead->created_by)->notify(new PushNotification(['message'=>$notiArray['body']]));
            $message='Dear '.$lead->contact_name.' Your Sale Person is '.Auth::user()->name.'
            .Email:'.Auth::user()->email.' Mobile:'.Auth::user()->mobile.' Details and feedbacks call 03111381888. Thanks and Regards: Tourvision Travel Pvt Ltd.';
            $this->notificationService->send_sms4_connect($lead->mobile, $message);
            return redirect('lms/lead/' . $leadId . '')->with('message', 'Lead Takenover Successfully..!!');
        }
    }
    public function lead_conversation($data,$id)
    {
        if($data->method()=="GET"){
            $ret=LeadConversation::with('user')->where('leadId',$id)->orderBy('id','DESC')->get();
            return $ret;
        }
        else if (isset($data->leadId)) {
            $con['leadId'] = $data->leadId;
            $con['conversation'] = $data->message;
            $con['contact_via'] = $data->contact_via;
            $ret = LeadConversation::create($con);
            if($ret){
                Lead::where("id",$data->leadId)->update(['BOXID'=>$data->BOXID]);
            }
            if($ret && !empty($data->reminder_date)){
                $rem['leadId']=$data->leadId;
                $rem['message']=$data->message;
                $rem['reminder_date']=date('Y-m-d',strtotime($data->reminder_date));
                $rem['reminder_time']=date('h:i:s',strtotime($data->reminder_time));
                LeadReminder::create($rem);
                // Given timestamp
                $given_timestamp = strtotime("2024-02-39 09:10:10");
                    // Current timestamp
                $current_timestamp = time();
                    // Calculate the difference
                $difference_in_seconds =Carbon::createFromFormat('Y-m-d', '2020-02-02');
                dispatch(new \App\Jobs\LeadReminder())->delay($difference_in_seconds);
                // dispatch(new SendLeadEmail($mailData))->delay(now()->addSeconds(30));
            }
            $ret->user;
            return $ret;
        }
    }
    public function change_status($id, $status)
    {
        if($status=='4' || $status=='5'){
            $ret = Lead::where('id', $id)->update(['status'=>$status,'BOXID'=>'18']);
        }else{
            $ret = Lead::where('id', $id)->update(['status'=>$status]);
        }
        if ($ret) {
            $data['LID'] = $id;
            $data['action_status'] = $status;
            $data['action_by'] = Auth::user()->id;
            LeadActivity::create($data);
            return $ret;
        }
    }
    public function lead_reason($request){
        $leadId=$request->leadId;
        $ret = Lead::where('id', $leadId)->update(['status'=>5]);
        if ($ret) {
            $data['LID'] = $leadId;
            $data['action_status'] = 5;
            $data['comments']=$request->comment;
            $data['reason_id']=$request->reason_id;
            $data['action_by'] = Auth::user()->id;
            LeadActivity::create($data);
            return $ret;
        }
    }
    /**edit leads */
    public function edit($id){
        return Lead::find($id);
    }
    public function update($data,$id){
        DB::beginTransaction();
        $mobile=$data->mobile;
        $service_date_from=Helpers::db_date_format($data->service_date_from);
        $service_date_to=Helpers::db_date_format($data->service_date_to);
        $data['service_date_from']=$service_date_from;
        $data['service_date_to']=$service_date_to;
        $data['services'] = json_encode($data->services);
        $data['sectors'] = json_encode($data->sectors);
        $data['airlines'] = json_encode($data->airline);
        $SPO = $data['spo'];
        $data['spo'] = $SPO;
        $data=$data->except(['airline']);
        try {
            $ret = Lead::where('id',$id)->update($data);
            DB::commit();
            return $ret;
        } catch (QueryException $e) {
            DB::rollBack();
            dd($e);
        }
    }
    public function reopen_lead($id){
        return Lead::find($id);
    }
    public function lead_reopen($data){
        DB::beginTransaction();
        $id=$data->id;
        $service_date_from=Helpers::db_date_format($data->service_date_from);
        $service_date_to=Helpers::db_date_format($data->service_date_to);
        $data['service_date_from']=$service_date_from;
        $data['service_date_to']=$service_date_to;
        $data['services'] = json_encode($data->services);
        $data['sectors'] = json_encode($data->sectors);
        $data['airlines'] = json_encode($data->airline);
        $SPO = $data['spo'];
        $data['spo'] = $SPO;
        $data['status'] = '1';
        $data['BOXID'] = '0';
        $data['reopen_by']=Auth::user()->id;
        $data['reopen_at']=date('Y-m-d h:i:s');
        $data=$data->except(['airline']);
        try {
            $ret = Lead::where('id',$id)->update($data);
            DB::commit();
            return $ret;
        } catch (QueryException $e) {
            DB::rollBack();
            dd($e);
        }
    }
    public function transfer_lead($data){
        $leadId=$data->leadId;
        $spo=$data->spo;
        Lead::where('id',$leadId)->update(['spo'=>$spo,'status'=>'1','BOXID'=>0]);
        $notiArray=["title"=>'Lead Transfered','body'=>'Lead No:'.$leadId.' Transfer by '.Auth::user()->name.''];
        User::find($spo)->notify(new PushNotification(['message'=>$notiArray['body']]));
        $dataa['LID'] = $leadId;
        $dataa['action_status'] =1;
        $dataa['comments']="Lead Transfer";
        $dataa['action_by'] = Auth::user()->id;
        LeadActivity::create($dataa);
    }
    public function save_reminder($data){
        if(!empty($data->reminder_date)){
            LeadReminder::where('leadId',$data->leadId)->update(['status'=>1]);
            $rem['leadId']=$data->leadId;
            $rem['message']=$data->message;
            $rem['reminder_date']=date('Y-m-d',strtotime($data->reminder_date));
            $rem['reminder_time']=date('h:i:s',strtotime($data->reminder_time));
            LeadReminder::create($rem);
        }
    }
}
