<?php

use App\Models\Airline;
use App\Models\Lead;
use App\Models\LeadReminder;
use App\Models\Lms\SourceQuery;
use App\Models\Lms\SService;
use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
class Helpers{
    /**create function lead format */
    public static function leadId_fromat($id){
        if($id<10){
            return '0'.$id;
        }else{
            return $id;
        }
    }
    /**LeadID */
    public static function create_lead_code($id){
        return date('dmy').'-'.self::leadId_fromat($id);
    }
    /**Lead status */
    public static function lead_status($status=''){
        $array=[1=>'Pending',2=>'Taken Over',3=>'In Process',4=>'Successfull',5=>'Unsuccessfull'];
        foreach($array as $k=>$v){
           if($k==$status){
                return $v;
           }
        }
    }
    /**status badges */
    public static function lead_status_badge($status= ''){
        if($status==0){
            return '<span class="badge badge-pill badge-secondary">Pending</span>';
        }
        elseif($status==1){
            return '<span class="badge badge-pill bg-info">Working Leads</span>';
        }
        elseif($status==2){
            return '<span class="badge badge-pill bg-primary">Working Leads WhatsApp</span>';
        }
        elseif($status==3){
            return '<span class="badge badge-pill bg-dark">Pax Confirm Call Later</span>';
        }
        elseif($status==4){
            return '<span class="badge badge-pill bg-teal">Booking Confirm Pax Confirm Soon</span>';
        }
        elseif($status==5){
            return '<span class="badge badge-pill bg-secondary">Pax Confirm Working for Low Fares</span>';
        }
        elseif($status==6){
            return '<span class="badge badge-pill bg-purple">Pax will Visit branch</span>';
        }
        elseif($status==7){
            return '<span class="badge badge-pill bg-warning">waitig for special fares</span>';
        }elseif($status==8){
            return '<span class="badge badge-pill bg-gray">Plan delay call later</span>';
        }elseif($status==9){
            return '<span class="badge badge-pill bg-warning">Travel Document/issue/exipire/renew</span>';
        }elseif($status==10){
            return '<span class="badge badge-pill bg-dark">Fare & flight information share</span>';
        }elseif($status==11){
            return '<span class="badge badge-pill bg-default">Date change/refund issue</span>';
        }elseif($status==12){
            return '<span class="badge badge-pill bg-teal">Already buy ticket by us/need flight info</span>';
        }elseif($status==13){
            return '<span class="badge badge-pill bg-info">Duplicate lead or engage with other spo</span>';
        }elseif($status==14){
            return '<span class="badge badge-pill bg-danger">suggestion & complaint</span>';
        }elseif($status==15){
            return '<span class="badge badge-pill bg-primary">User not attended/agent hangup</span>';
        }elseif($status==16){
            return '<span class="badge badge-pill bg-success">ticket issued & processing</span>';
        }elseif($status==17){
            return '<span class="badge badge-pill bg-success">Umrah Leads</span>';
        }elseif($status==18 || $status==19){
            return '<span class="badge badge-pill bg-gradient-maroon">Closed Leads</span>';
        }
        else{
            return 'N/A';
        }
    }
    //get services naem against array
    public static function lead_services(array $ids){
        $res=SService::whereIn('id',$ids)->pluck('name');
        $res=json_decode($res);
        $html='';
        foreach($res as $item){
            $html.= '<span class="badge badge-info">'.$item.'</span> ';
        }
        return $html;
    }
    public static function airlines($ids=""){
        if(!empty($ids)){
        $res=Airline::whereIn('id',$ids)->pluck('name');
        $res=json_decode($res);
        $html='';
        foreach($res as $item){
            $html.= '<span class="badge badge-info">'.$item.'</span> ';
        }
        return $html;
        }else{
            return "";
        }
    }
    /**date format in day-month-year */
    public static function date_format($date){
        if($date!=null && !is_numeric($date) && $date!='1970-01-01') {
        return date('d-m-Y',strtotime($date));
        }else{
            return "N/A";
        }
    }
    /**days in string */
    public static function string_day($date){
        return date('d M Y',strtotime($date));
    }
    /**Fetch only time from date  */
    public static function fetch_time($time=''){
        return date('h:i:s',strtotime($time));
    }
    /**calculate time difference between two dates */
    public static function calculateDateTimeDifference($startDateTime, $endDateTime) {
        $start = new DateTime($startDateTime);
        $end = new DateTime($endDateTime);

        $interval = $start->diff($end);

        $days = $interval->format('%a');
        $hours = $interval->format('%h');
        $minutes = $interval->format('%i');
        $seconds = $interval->format('%s');

        return "$days days, $hours hours, $minutes minutes";
    }
    /**Fetch Lead source */
    public static function fetch_lead_source($source){
        if(!empty($source)){
            return SourceQuery::find($source)->name;
        }
    }
    public static function helper_dropdown($id=''){
        $list='';
        $res=Role::all();
        foreach($res as $item){
            $list.='<option '.($id==$item->id?"selected":"").' value="'.$item->id.'">'.$item->name.'</option>';
        }
        return $list;
    }
    /**Lead Closed Reason */
    public static function closed_reason(){
        $list='';
        $array=[1=>'Client not Intersted',2=>'Client Avail Service from somewhere',3=>'Fake Lead',
        4=>'Without Info',5=>'Other'];
        foreach($array as $key=>$val){
            $list.='<option value="'.$key.'">'.$val.'</option>';
        }
        return $list;
    }
    /**db date format */
    public static function db_date_format($date=''){
        $date=date('Y-m-d',strtotime($date));
        return $date;
    }
    //convert sectors to string
    public static function sectors($sectors=''){
        if(isset($sectors)){
        $string = implode("-", $sectors);
        return $string;
        }else{
            return "";
        }
    }
    public static function status($status=''){
        $array=[0=>'Inactive',1=>'Active'];
        $list='';
        foreach($array as $key=>$val){
            $list.='<option '.($status==$key?'selected':'').' value="'.$key.'">'.$val.'</option>';
        }
        return $list;
    }
    public static function lead_priority($p){
        if($p==1){
            return '<span class="badge badge-info">This is Low priority Lead</span>';
        }
        elseif($p==2){
            return '<span class="badge badge-warning">This is Medium priority Lead</span>';
        }
        elseif($p==3){
            return '<span class="badge badge-danger">This is High priority Lead</spa>';
        }else{
            return 'N/A';
        }
    }
    //fetch Reminder notifications
    public static function fetch_reminder_notification(){
        date_default_timezone_set("Asia/Karachi");
        $current_date=date("Y-m-d");
        DB::connection()->enableQueryLog();
        $count=LeadReminder::where("status",0)->where("created_by",Auth::user()->id)->whereDate("reminder_date",$current_date)
        ->whereRaw('HOUR(reminder_time) = ? AND MINUTE(reminder_time)<=?', [date('h'),date('i')])->count();
        return $count;
    }
    public static function lead_boxes(){
        $boxes=config('constant.lead_boxes');
        return $boxes;
    }
}
