<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\CreateLeadRequest;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadReminder;
use App\Models\Lms\LeadConversation;
use App\Repositories\Interfaces\LeadRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Helpers;
use Twilio\Rest\Client;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Str;

class LeadController extends Controller
{
    private $leadInterface;
    public function __construct(LeadRepositoryInterface $leadInterface)
    {
        $this->leadInterface = $leadInterface;
        $this->middleware('permission:lead_add|lead_edit', ['only' => ['my_leads']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leadsCountByMonth = DB::table('leads')
    ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
    ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
    ->get();
        $pending_leads=$this->leadInterface->lead_boxes(1);
        $takenover_leads=$this->leadInterface->lead_boxes(2);
        $inprocess_leads=$this->leadInterface->lead_boxes(3);
        $successfull_leads=$this->leadInterface->lead_boxes(4);
        $unSuccessfull_leads=$this->leadInterface->lead_boxes(5);
        $all_leads=$this->leadInterface->lead_boxes(0);
        // dd($leadsCountByMonth);
        return view('Lms.index',compact('pending_leads','takenover_leads',
        'inprocess_leads','successfull_leads','unSuccessfull_leads','all_leads','leadsCountByMonth'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Lms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLeadRequest $request)
    {
        $data = $request->all();
        $lead = $this->leadInterface->store($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data=$this->leadInterface->show($id);
        $lead_conversation=LeadConversation::where('leadId',$id)->latest()->first();
        $lead_activity=LeadActivity::where("LID",$id)->orderBy('id','DESC')->get();
        $recent_reminder=LeadReminder::where('leadId',$id)->latest()->first();
        if($lead_conversation){
            $conversation=$lead_conversation;
        }else{
            $conversation='';
        }
        return view('Lms.show',compact('data','conversation','lead_activity','recent_reminder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data=$this->leadInterface->edit($id);
        return view('Lms.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->leadInterface->update($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->leadInterface->destroy($id);
    }
    //All Leads
    public function all_leads(Request $request)
    {
        $pending_leads=$this->leadInterface->lead_boxes(1);
        $takenover_leads=$this->leadInterface->lead_boxes(2);
        $inprocess_leads=$this->leadInterface->lead_boxes(3);
        $successfull_leads=$this->leadInterface->lead_boxes(4);
        $unSuccessfull_leads=$this->leadInterface->lead_boxes(5);
        $all_leads=$this->leadInterface->lead_boxes(0);
        $boxCounts = Lead::select('BOXID', DB::raw('count(*) as count'))
                ->groupBy('BOXID')->get();

        if ($request->ajax()) {
            $res = Lead::select('*')->with(['leadSpo','latestConversation'])->orderBy('id','DESC');
            if(isset($request->BOXID) && $request->BOXID=='18'){
                $res->whereIn("BOXID",['18','19']);
            }
            elseif(isset($request->BOXID) && $request->BOXID=='20'){

            }
            else{
                if(isset($request->BOXID)){
                    $res->where("BOXID",$request->BOXID);
                }
                if(isset($request->leadId)){
                    $res->where("id",$request->leadId);
                }
                if(isset($request->mobile)){
                    $mobile=str_replace(' ','',$request->mobile);
                    $res->where("mobile",'LIKE',"%{$mobile}%");
                }
            }
            return DataTables::of($res)
                ->addIndexColumn()
                ->addColumn('spo_name', function ($row) {
                    if ($row->leadSpo != null) {
                        return $row->leadSpo->name;
                    } else {
                        return 'N/A';
                    }
                })->addColumn('leadId', function ($row) {
                    return '<button  data-conversation="'.htmlspecialchars($row->latestConversation->conversation??"",ENT_QUOTES).'" class="lead-remarks btn btn-link">'.Helpers::leadId_fromat($row->id).'</button>';
                })->addColumn('lead_status', function ($row) {
                    return Helpers::lead_status_badge($row->BOXID);
                })
                ->addColumn('remarks',function($row){
                    // return htmlspecialchars($row->latestConversation->conversation??"",ENT_QUOTES);!=
                    if (isset($row->latestConversation)) {
                        $conversation = $row->latestConversation->conversation ?? "";
                        $cleanConversation = strip_tags($conversation);
                        return substr($cleanConversation, 0, 50);
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                Action
                              <span class="sr-only">Toggle Dropdown</span></button>
                              <div class="dropdown-menu" role="menu" style="">';
                              if (auth()->user()->can('lead_edit')){
                                $btn.='<a class="dropdown-item" href="' . route('lead.edit', $row->id) . '"><i class="fas fa-edit"></i> Edit</a>';
                            }
                    $btn.='<a class="dropdown-item"  tabindex="-1" class="disabled" target="_blank"  href="'.route('lead.show',$row->id).'"><i class="fas fa-eye"></i> View</a>
                                '.(($row->BOXID==0)?'<a class="dropdown-item" id="lead-takeover" href="javascript:void(0)" data-id="' . $row->id . '"><i class="fas fa-sync-alt"></i> '. __('lms.takenover').'</a>':'').'
                                <a class="dropdown-item text-danger del_rec" href="javascript:void(0)" data-id="'.$row->id.'" data-action="'.url('lms/lead').'"><i class="fas fa-trash"></i> Delete</a>
                              </div>

                          </div>';
                    return $btn;
                })
                ->rawColumns(['action', 'spo_name','lead_status','leadId'])
                ->make(true);
        }
        return view('Lms.all_leads',compact('pending_leads','takenover_leads',
        'inprocess_leads','successfull_leads','unSuccessfull_leads','all_leads','boxCounts'));
    }
    //my Leads
    public function my_leads(Request $request)
    {
        \DB::enableQueryLog();
        $pending_leads=$this->leadInterface->lead_boxes(1);
        $takenover_leads=$this->leadInterface->lead_boxes(2);
        $inprocess_leads=$this->leadInterface->lead_boxes(3);
        $successfull_leads=$this->leadInterface->lead_boxes(4);
        $unSuccessfull_leads=$this->leadInterface->lead_boxes(5);
        $all_leads=$this->leadInterface->lead_boxes(0);
        $boxCounts = Lead::where('spo',Auth::user()->id)->orWhere('created_by',Auth::user()->id)
        ->select('BOXID', DB::raw('count(*) as count'))->groupBy('BOXID')->get();
                // dd($boxCounts);
                Log::debug(DB::getQueryLog());
        if ($request->ajax()) {
            $res = Lead::select('*')
            ->with(['leadSpo','latestConversation'])
            ->where(function($query) {
                $query->where('spo', Auth::user()->id)
                    ->orWhere('created_by', Auth::user()->id);
            })->where(function($query) use ($request) {
                if(isset($request->BOXID) && $request->BOXID!=18 && $request->BOXID!=20) {
                    $query->where('BOXID', $request->BOXID);
                }
                elseif(isset($request->BOXID) && $request->BOXID=='20'){

                }
                elseif(isset($request->BOXID) && $request->BOXID==18) {
                    $query->whereIn('BOXID', ['18','19']);
                }else{
                    if(isset($request->BOXID)){
                        $query->where("BOXID",$request->BOXID);
                    }
                    if(isset($request->leadId)){
                        $query->where("id",$request->leadId);
                    }
                    if(isset($request->mobile)){
                        $mobile=str_replace(' ','',$request->mobile);
                        $query->where("mobile",'LIKE',"%{$mobile}%");
                    }
                    if(!isset($request->BOXID) && !isset($request->leadId) && !isset($request->mobile)){
                        $query->where("BOXID",'0');
                    }
                }
            })->orderByRaw('leads.id DESC')->get();
            Log::debug(DB::getQueryLog());
            return DataTables::of($res)
                ->addIndexColumn()
                ->addColumn('spo_name', function ($row) {
                    if ($row->leadSpo != null) {
                        return $row->leadSpo->name;
                    } else {
                        return 'N/A';
                    }
                })->addColumn('leadId', function ($row) {
                    return Helpers::leadId_fromat($row->id);
                })->addColumn('lead_status', function ($row) {
                    return Helpers::lead_status_badge($row->BOXID);
                })->addColumn('remarks',function($row){
                    if (isset($row->latestConversation)) {
                        return $conversation = Str::limit($row->latestConversation->conversation,50) ?? "";
                        // // $cleanConversation = utf8_encode(strip_tags($conversation));
                        // $cleanConversation=mb_convert_encoding($conversation, 'UTF-8', 'UTF-8');
                        // if($cleanConversation){
                        //     return substr($cleanConversation, 0, 50);
                        // }else{
                        //     return 'N/A';
                        // }
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                Action
                              <span class="sr-only">Toggle Dropdown</span></button>';
                              $btn.='
                              <div class="dropdown-menu" role="menu" style="">';
                              if(auth()->user()->can('lead_edit')){
                                $btn.='<a class="dropdown-item" onClick="edit_rec(this)" data-action="' . route('source.edit', $row->id) . '" href="#" data-modal="add-new" data-id="' . $row->id . '"><i class="fas fa-edit"></i> Edit</a>';
                              }
                            $btn.='<a class="dropdown-item"  tabindex="-1" class="disabled" target="_blank"  href="'.route('lead.show',$row->id).'"><i class="fas fa-eye"></i> View</a>';
                            $btn.='
                                '.(($row->BOXID==0)?'<a class="dropdown-item" id="lead-takeover" href="javascript:void(0)" data-id="' . $row->id . '"><i class="fas fa-sync-alt"></i> '. __('lms.takenover').'</a>':'').'
                                ';
                            if(auth()->user()->can('lead_delete')){
                                $btn.='<a class="dropdown-item text-danger del_rec" href="javascript:void(0)" data-id="'.$row->id.'" data-action="'.url('lms/lead').'"><i class="fas fa-trash"></i> Delete</a>';
                            }
                              $btn.='</div>
                          </div>';
                    return $btn;
                })
                ->rawColumns(['action', 'spo_name','lead_status','remarks'])
                ->make(true);
        }
        return view('Lms.my_leads',compact('pending_leads','takenover_leads',
        'inprocess_leads','successfull_leads','unSuccessfull_leads','all_leads','boxCounts'));
    }
    /**
     * show all lead activity
     */
    public function lead_activity($id = 0)
    {
        return view('Lms.lead_activity');
    }
    /**
     * check lead against mobile number
     */
    public function check_lead($mobile_number)
    {
        return $this->leadInterface->check_lead($mobile_number);
    }
    /**Takeover leads */
    public function takeover_lead(Request $request){
        $data=$request->all();
        return $this->leadInterface->takeover_lead($data);
    }
    /**Client conversation as client asking about requirements */
    public function lead_conversation(Request $request,$id=''){
        if($request->method()=="POST"){
            $validatedData = $request->validate([
                'message' => 'required',
            ], [
                'message.required' => 'Conversation required',
            ]);
            return $this->leadInterface->lead_conversation($request,$id);
        }else{
            if( $request->method()=="GET"){
                $data=$request;
                return $this->leadInterface->lead_conversation($data,$id);
            }
        }
    }
    public function change_status($id, $status){
        if($status!=''){
            $res=$this->leadInterface->change_status($id,$status);
            return $res;
        }
    }
    public function lead_reason(Request $request){
        return $this->leadInterface->lead_reason($request);
    }
    public function reopen_lead($id){
        $data=$this->leadInterface->reopen_lead($id);
        return view('Lms.reopen',compact('data'));
    }
    public function lead_reopen(Request $request){
        $this->leadInterface->lead_reopen($request);
    }
    public function transfer_lead(Request $request){
    $this->leadInterface->transfer_lead($request);
    }
    public function lead_reminder(Request $request,$type=null){
        if ($request->ajax()) {
            $res = LeadReminder::with("lead")->select('*')->where("created_by",Auth::user()->id)->orderBy('id','DESC');
            if($request->type==1){
                $res=$res->where("status",0);
            }
            if($request->type==3 && isset($request->leadId)){
                $res=$res->where("status",0)->where('leadId',$request->leadId);
            }
            return DataTables::of($res)
                ->addIndexColumn()
               ->addColumn('status', function ($row) {
                    return ($row->status==0?"pending":"read");
                })
                ->addColumn('reminder_date', function ($row) {
                    return (date('d-m-Y',strtotime($row->reminder_date))." ".$row->reminder_time);
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                Action
                              <span class="sr-only">Toggle Dropdown</span></button>';
                              $btn.='
                              <div class="dropdown-menu" role="menu" style="">';
                            $btn.='<a class="dropdown-item lead-reminder-update" href="#" data-modal="add-new" data-id="' . $row->id . '"><i class="fas fa-edit"></i> Update</a>';
                            if($row->status==0){
                                $btn.='<a class="dropdown-item reminder-read"  tabindex="-1" class="disabled" data-id="' . $row->id . '"><i class="fas fa-check"></i> Mark As Read</a>';
                            }
                              $btn.='</div>
                          </div>';
                    return $btn;
                })
                ->rawColumns(['action', 'spo_name','lead_status'])
                ->make(true);
        }
        return view('Lms.lead_reminder',compact('type'));
    }
    public function reminder_read(Request $request){
        $id=$request->id;
        LeadReminder::where("id",$id)->update(['status'=>1]);
    }
    public function edit_reminder(Request $request){
        $id=$request->id;
        return LeadReminder::find($id);
    }
    public function save_reminder(Request $request){
        $this->leadInterface->save_reminder($request);
    }
    public function reopen_leads(Request $request){
        if ($request->ajax()) {
            $res = Lead::select('*')
            ->with(['leadSpo','latestConversation'])
            ->where(function($query) {
                $query->where('spo', Auth::user()->id)
                    ->orWhere('created_by', Auth::user()->id);
            })->where(function($query) use ($request) {
                if(isset($request->leadId)){
                    $query->where("id",$request->leadId);
                }
                if(isset($request->mobile)){
                    $mobile=str_replace(' ','',$request->mobile);
                    $query->where("mobile",'LIKE',"%{$mobile}%");
                }
                $query->whereNotNull('reopen_at');
            })->orderByRaw('leads.id DESC')->get();
            // Log::debug(DB::getQueryLog());
            return DataTables::of($res)
                ->addIndexColumn()
                ->addColumn('spo_name', function ($row) {
                    if ($row->leadSpo != null) {
                        return $row->leadSpo->name;
                    } else {
                        return 'N/A';
                    }
                })->addColumn('leadId', function ($row) {
                    return Helpers::leadId_fromat($row->id);
                })->addColumn('lead_status', function ($row) {
                    return Helpers::lead_status_badge($row->BOXID);
                })->addColumn('remarks',function($row){
                    if (isset($row->latestConversation)) {
                        return $conversation = Str::limit($row->latestConversation->conversation,50) ?? "";
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                Action
                              <span class="sr-only">Toggle Dropdown</span></button>';
                              $btn.='
                              <div class="dropdown-menu" role="menu" style="">';
                              if(auth()->user()->can('lead_edit')){
                                $btn.='<a class="dropdown-item" onClick="edit_rec(this)" data-action="' . route('source.edit', $row->id) . '" href="#" data-modal="add-new" data-id="' . $row->id . '"><i class="fas fa-edit"></i> Edit</a>';
                              }
                            $btn.='<a class="dropdown-item"  tabindex="-1" class="disabled" target="_blank"  href="'.route('lead.show',$row->id).'"><i class="fas fa-eye"></i> View</a>';
                            $btn.='
                                '.(($row->BOXID==0)?'<a class="dropdown-item" id="lead-takeover" href="javascript:void(0)" data-id="' . $row->id . '"><i class="fas fa-sync-alt"></i> '. __('lms.takenover').'</a>':'').'
                                ';
                            if(auth()->user()->can('lead_delete')){
                                $btn.='<a class="dropdown-item text-danger del_rec" href="javascript:void(0)" data-id="'.$row->id.'" data-action="'.url('lms/lead').'"><i class="fas fa-trash"></i> Delete</a>';
                            }
                              $btn.='</div>
                          </div>';
                    return $btn;
                })
                ->rawColumns(['action', 'spo_name','lead_status','remarks'])
                ->make(true);
        }
        return view('Lms.reopen_leads');
    }
}
