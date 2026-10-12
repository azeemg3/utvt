<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::select('*')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('price', function ($row) {
                    return number_format((float) $row->price, 2);
                })
                ->editColumn('status', function ($row) {
                    return (int) $row->status === 1
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-secondary">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            Action <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" onClick="edit_rec(this)" data-action="' . route('product.edit', $row->id) . '" href="#" data-modal="add-new" data-id="' . $row->id . '"><i class="fas fa-edit"></i> Edit</a>
                            <a class="dropdown-item text-danger del_rec" href="javascript:void(0)" data-id="' . $row->id . '" data-action="' . url('invoice/product') . '"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('invoice.products.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ], [
            'name.required' => 'The Name field is required',
            'price.required' => 'The Price field is required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['name', 'sku', 'price', 'description', 'status']);
            $data['status'] = $request->input('status', 1);
            $id = $request->id;

            if ($id == 0 || $id == '') {
                $ret = Product::create($data);
            } else {
                Product::where('id', $id)->update($data);
                $ret = Product::find($id);
            }

            DB::commit();
            return response()->json($ret);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Unable to save product'], 500);
        }
    }

    public function edit(string $id)
    {
        return Product::findOrFail($id);
    }

    public function destroy(string $id)
    {
        Product::destroy($id);
        return response()->json(['success' => true]);
    }

    public function listActive()
    {
        return response()->json(Product::activeList());
    }
}
