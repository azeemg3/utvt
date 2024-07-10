<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\SubHeadAccount;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;

class SubHeadAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubHeadAccount::select('*')->with('head_acc');
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
                        <a class="dropdown-item text-danger del_rec" href="javascript:void(0)" data-id="'.$row->id.'" data-action="'.route('airline.store').'"><i class="fas fa-trash"></i> Delete</a>
                      </div>
                  </div>';
                  return $btn;
                })
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }
        return view('Accounts.subhead_accounts.index');
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
            'HID'=>'required',
            'name' => 'required|unique:sub_head_accounts,name',
        ];
        $message=[
            'HID.required'=>'Head Account Required',
            'name.required'=>'Subhead Account Required',
        ];
        $this->validate($request, $rules, $message);
        $data=$request->except(['_token']);
        $id=$request->id;
        DB::beginTransaction();
        try {
            if ($id == '' || $id == 0) {
                SubHeadAccount::create($data);
            } else {
                SubHeadAccount::where('id', $id)->update($data);
            }
            DB::commit();
            return response()->json(['success' => 'Added new record Successfully.']);

        }catch (\Illuminate\Database\QueryException $e){
            $code = $e->errorInfo[1];
            return response()->json([
                'success' => 'false',
                'errors'  => $e->errorInfo,
            ], 400);
            DB::rollback();
        }
    }
    //@listing data
    public function get_data(Request $request){
        return DB::table('sub_head_accounts')->join('head_accounts', 'sub_head_accounts.HID','head_accounts.id')
            ->join('root_accounts','head_accounts.RID','root_accounts.id')
            ->select('sub_head_accounts.*','head_accounts.name AS head_name', 'root_accounts.name AS root_name')
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
        return SubHeadAccount::find($id);
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
        //
    }
}
