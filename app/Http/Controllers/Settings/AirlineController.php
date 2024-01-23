<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;
class AirlineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Airline::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="group-checkable" value="">';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        Action
                      <span class="sr-only">Toggle Dropdown</span></button>
                      <div class="dropdown-menu" role="menu" style="">
                        <a class="dropdown-item" onClick="edit_rec(this)" data-action="' . route('airline.edit', $row->id) . '" href="#" data-modal="add-new" data-id="' . $row->id . '"><i class="fas fa-edit"></i> Edit</a>
                        <a class="dropdown-item text-danger del_rec" href="javascript:void(0)" data-id="'.$row->id.'" data-action="'.route('airline.store').'"><i class="fas fa-trash"></i> Delete</a>
                      </div>

                  </div>';
                  return $btn;
                })
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }
        return view('settings.airline.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'The Name Field is required',
        ]);
        DB::beginTransaction();
        $data = $request->all();
        $id = $request->id;
        try {
            if ($id == 0 || $id == '') {
                $ret = Airline::create($data);
            }else{
                $ret=Airline::where('id',$id)->update($data);
            }
            DB::commit();
            return $ret;
        }catch (QueryException $e) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Airline::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Airline::destroy($id);
    }
}
