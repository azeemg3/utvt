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
    /**Lead status labels (pipeline) */
    public static function lead_status($status=''){
        $array = [
            1 => 'Pending',
            2 => 'Taken Over',
            3 => 'Working on Leads',
            4 => 'Successful',
            5 => 'Unsuccessful',
        ];
        return $array[(int) $status] ?? 'N/A';
    }

    /** Status badges for all_leads / lead detail (uses leads.status) */
    public static function lead_status_badge($status= ''){
        $status = (int) $status;
        if ($status === 0 || $status === 1) {
            return '<span class="badge badge-pill badge-secondary">Pending</span>';
        }
        if ($status === 2) {
            return '<span class="badge badge-pill bg-primary">Taken Over</span>';
        }
        if ($status === 3) {
            return '<span class="badge badge-pill bg-info">Working on Leads</span>';
        }
        if ($status === 4) {
            return '<span class="badge badge-pill bg-success">Successful</span>';
        }
        if ($status === 5) {
            return '<span class="badge badge-pill bg-danger">Unsuccessful</span>';
        }
        return '<span class="badge badge-pill badge-secondary">N/A</span>';
    }
    //get services naem against array
    public static function lead_services($ids){
        if(empty($ids) || !is_array($ids)){
            return 'N/A';
        }
        $res=SService::whereIn('id',$ids)->pluck('name');
        $res=json_decode($res);
        $html='';
        foreach($res as $item){
            $html.= '<span class="badge badge-info">'.$item.'</span> ';
        }
        return $html ?: 'N/A';
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
            return optional(SourceQuery::find($source))->name;
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
        $array=[
            1=>'Client not interested',
            2=>'Chose another contractor',
            3=>'Fake lead',
            4=>'Incomplete information',
            5=>'Budget mismatch',
            6=>'Project postponed',
            7=>'Other',
        ];
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
    //convert sectors / property types / emirates to string
    public static function sectors($sectors=''){
        if(!empty($sectors) && is_array($sectors)){
            $html = '';
            foreach ($sectors as $item) {
                $html .= '<span class="badge badge-info">'.$item.'</span> ';
            }
            return $html;
        }
        return "N/A";
    }

    public static function budget_range($value=''){
        $labels = [
            1 => 'Under AED 25,000',
            2 => 'AED 25,000 – 75,000',
            3 => 'AED 75,000 – 150,000',
            4 => 'Above AED 150,000',
            5 => 'Not specified',
        ];
        return $labels[$value] ?? 'N/A';
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
        date_default_timezone_set(config('app.timezone', 'UTC'));
        $current_date=date("Y-m-d");
        $now = date('Y-m-d H:i:s');
        $count=LeadReminder::where("status",0)
            ->where("created_by",Auth::user()->id)
            ->whereDate("reminder_date","<=",$current_date)
            ->whereRaw("CONCAT(reminder_date, ' ', reminder_time) <= ?", [$now])
            ->count();
        return $count;
    }
    public static function lead_boxes(){
        $boxes=config('constant.lead_boxes');
        return $boxes;
    }

    /** Convert number to English words (for invoice totals) */
    public static function amount_in_words($amount)
    {
        $amount = round((float) $amount, 2);
        $ones = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
            'Seventeen', 'Eighteen', 'Nineteen',
        ];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $convertBelowThousand = function ($n) use (&$convertBelowThousand, $ones, $tens) {
            $n = (int) $n;
            if ($n < 20) {
                return $ones[$n];
            }
            if ($n < 100) {
                return trim($tens[(int) ($n / 10)] . ' ' . $ones[$n % 10]);
            }
            return trim($ones[(int) ($n / 100)] . ' Hundred ' . $convertBelowThousand($n % 100));
        };

        $convert = function ($n) use (&$convert, $convertBelowThousand) {
            $n = (int) $n;
            if ($n === 0) {
                return 'Zero';
            }
            if ($n < 1000) {
                return $convertBelowThousand($n);
            }
            if ($n < 1000000) {
                return trim($convertBelowThousand((int) ($n / 1000)) . ' Thousand ' . $convertBelowThousand($n % 1000));
            }
            return trim($convert((int) ($n / 1000000)) . ' Million ' . $convert($n % 1000000));
        };

        $intPart = (int) floor($amount);
        $fils = (int) round(($amount - $intPart) * 100);
        $words = $convert($intPart) . ' Dirhams';
        if ($fils > 0) {
            $words .= ' and ' . $convert($fils) . ' Fils';
        }
        return $words . ' Only';
    }
}
