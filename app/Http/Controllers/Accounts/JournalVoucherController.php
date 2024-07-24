<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Agent;
use Illuminate\Http\Request;
use App\Models\Accounts\Transaction;
use App\Helpers\Account;
use App\Models\Accounts\JournalVoucher;
use App\Models\Accounts\TransactionAccount;
use DB;
use Auth;
use Yajra\DataTables\DataTables;

class JournalVoucherController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:jv_view', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JournalVoucher::select('*')->with('trans_acc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="group-checkable" value="">';
                })->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        Action
                      <span class="sr-only">Toggle Dropdown</span></button>
                      <div class="dropdown-menu" role="menu" style="">
                        <a class="dropdown-item" onClick="edit('.$row->id.')"><i class="fas fa-edit"></i> Edit</a>
                        <a class="dropdown-item text-danger del_rec" href="javascript:void(0)" data-id="'.$row->id.'" data-action="'.route('journal_vouchers.store').'"><i class="fas fa-trash"></i> Delete</a>
                      </div>
                  </div>';
                  return $btn;
                })
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }
        return view('Accounts.vouchers.journal_vouchers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules=[
            'trans_date'=>'required',
            'payment_to'=>'required',
            'payment_type'=>'required',
        ];
        $message=[
            'trans_date.required'=>'Transaction Date Required',
            'payment_to.required'=>'Bank/Cash Account Required',
            'payment_type.required'=>'Payment Type Required',
        ];
        $this->validate($request, $rules, $message);
        $data=$request->except(['_token','narration','OB']);
        $id=$request->id;
        //account entry
        $tData['trans_date']=$request->trans_date;
        $tData['posting_date']=$request->posting_date;
        $tData['payment_to']=$request->payment_to;
        $tData['payment_from']=$request->payment_from;
        $tData['narration']=$request->narration;
        $tData['amount']=$request->amount;
        $tData['payment_type']=$request->payment_type;
        $tData['status']=1;
        $tData['vt']=2;
        $tData['trans_code']=Account::trans_code();
        DB::beginTransaction();
        try {
            if ($id == '' || $id == 0) {
                $data['trans_code']=Account::trans_code();
                $data['created_by']=Auth::user()->id;
                $data['remarks']=$request->narration;
                $ret=JournalVoucher::create($data);
                $tData['Created_By']=Auth::user()->id;
                //dr to cash bank
                $tData['trans_acc_id']=$request->payment_to;
                $tData['dr_cr']=1;
                Transaction::create($tData);
                //cr to client
                $tData['trans_acc_id']=$request->payment_from;
                $tData['dr_cr']=2;
                Transaction::create($tData);
            } else {
                $PID=Agent::where('id', $id)->value('PID');
                Agent::where('id', $id)->update($data);
                $tData['updated_by']=Auth::user()->id;
                TransactionAccount::where(['Parent_Type'=>$id,'PID'=>$PID])->update($tData);
            }
            DB::commit();
        }catch (\Illuminate\Database\QueryException $e){
            $code = $e->errorInfo[1];
            return response()->json([
                'success' => 'false',
                'errors'  => $e->errorInfo,
                'code'  => $e->errorInfo,
            ], 400);
            DB::rollback();
        }
        return response()->json(['success' => 'Added new record Successfully.']);
    }
        //@listing data
        public function get_data(Request $request){
        return JournalVoucher::whereBetween(DB::raw('DATE(created_at)'),Account::financial_year())
            ->paginate(15);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return JournalVoucher::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        JournalVoucher::where('trans_code', $id)->delete();
        Transaction::where('trans_code', $id)->delete();
    }
}
