<?php

namespace App\Http\Controllers\Accounts;

use App\Helpers\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounts\TransactionAccount;
use DB;
use Config;
use Yajra\DataTables\DataTables;
class TransAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TransactionAccount::select('*')->with('subhead');
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
                        <a class="dropdown-item text-danger del_rec" href="javascript:void(0)" data-id="'.$row->id.'" data-action="'.route('trans_accounts.store').'"><i class="fas fa-trash"></i> Delete</a>
                      </div>
                  </div>';
                  return $btn;
                })
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }
        return view('Accounts.trans_accounts.index');
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
            'PID'=>'required',
            'Trans_Acc_Name' => 'required|unique:transaction_accounts,Trans_Acc_Name',
        ];
        $message=[
            'PID.required'=>'A/C Type Required',
            'Trans_Acc_Name.required'=>'Trans Account Required',
        ];
        $this->validate($request, $rules, $message);
        $data=$request->except(['_token', 'password', 'roles']);
        $data['editable']=1;
        $id=$request->id;
        DB::beginTransaction();
        try {
            if ($id == '' || $id == 0) {
                $ret=TransactionAccount::create($data);
                TransactionAccount::where('id',$ret->id)->update(['code'=>Account::current_code('',$ret->id)]);

            } else {
                TransactionAccount::where('id', $id)->update($data);
            }
            DB::commit();
            return response()->json(['success' => 'Added new record Successfully.']);

        }catch (\Illuminate\Database\QueryException $e){
            $code = $e->errorInfo[1];
            return response()->json([
                'success' => 'false',
                'errors'  => $e->errorInfo,
            ], 400);
        }
    }
    //listing data
    public function get_data(Request $request){
        return TransactionAccount::with('subhead')
            ->when($request->trans_acc, function($query) use ($request){
                $query->where('Trans_Acc_Name', 'LIKe', '%'.$request->trans_acc.'%');
            })
            ->orderByDesc('id')->paginate(Config::get('constant.pagination_count'));
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
        return TransactionAccount::find($id);
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
        return TransactionAccount::destroy($id);
    }
}
