<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Invoice::with('createdBy')->select('invoices.*')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('invoice_date', function ($row) {
                    return optional($row->invoice_date)->format('d-m-Y');
                })
                ->editColumn('total', function ($row) {
                    return number_format((float) $row->total, 2);
                })
                ->addColumn('status_badge', function ($row) {
                    return $row->statusBadge();
                })
                ->addColumn('created_by_name', function ($row) {
                    return $row->createdBy->name ?? '-';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            Action <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="' . route('invoice.show', $row->id) . '"><i class="fas fa-eye"></i> View</a>
                            <a class="dropdown-item" href="' . route('invoice.edit', $row->id) . '"><i class="fas fa-edit"></i> Edit</a>
                            <a class="dropdown-item text-danger del_rec" href="javascript:void(0)" data-id="' . $row->id . '" data-action="' . url('invoice/invoice') . '"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </div>';
                })
                ->rawColumns(['action', 'status_badge'])
                ->make(true);
        }

        return view('invoice.invoices.index');
    }

    public function create()
    {
        $products = Product::activeList();
        return view('invoice.invoices.form', [
            'invoice' => null,
            'products' => $products,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateInvoice($request);

        DB::beginTransaction();
        try {
            $totals = $this->calculateTotals($validated);

            $invoice = Invoice::create([
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_address' => $validated['customer_address'] ?? null,
                'customer_trn' => $validated['customer_trn'] ?? null,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'] ?? null,
                'subtotal' => $totals['subtotal'],
                'tax_percent' => $totals['tax_percent'],
                'tax_amount' => $totals['tax_amount'],
                'discount' => $totals['discount'],
                'total' => $totals['total'],
                'status' => $validated['status'] ?? 'unpaid',
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->syncItems($invoice, $validated['items']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'redirect' => route('invoice.show', $invoice->id),
            ]);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Unable to create invoice'], 500);
        }
    }

    public function show(string $id)
    {
        $invoice = Invoice::with(['items.product', 'createdBy'])->findOrFail($id);
        return view('invoice.invoices.show', compact('invoice'));
    }

    public function edit(string $id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $products = Product::activeList();

        return view('invoice.invoices.form', [
            'invoice' => $invoice,
            'products' => $products,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $validated = $this->validateInvoice($request);

        DB::beginTransaction();
        try {
            $totals = $this->calculateTotals($validated);

            $invoice->update([
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_address' => $validated['customer_address'] ?? null,
                'customer_trn' => $validated['customer_trn'] ?? null,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'] ?? null,
                'subtotal' => $totals['subtotal'],
                'tax_percent' => $totals['tax_percent'],
                'tax_amount' => $totals['tax_amount'],
                'discount' => $totals['discount'],
                'total' => $totals['total'],
                'status' => $validated['status'] ?? 'unpaid',
                'notes' => $validated['notes'] ?? null,
            ]);

            $invoice->items()->delete();
            $this->syncItems($invoice, $validated['items']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice updated successfully',
                'redirect' => route('invoice.show', $invoice->id),
            ]);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Unable to update invoice'], 500);
        }
    }

    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return response()->json(['success' => true]);
    }

    private function validateInvoice(Request $request): array
    {
        return $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'customer_address' => 'nullable|string',
            'customer_trn' => 'nullable|string|max:100',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,unpaid,paid,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ], [
            'customer_name.required' => 'Customer name is required',
            'items.required' => 'Please add at least one product',
            'items.*.product_id.required' => 'Please select a product for each line',
        ]);
    }

    private function calculateTotals(array $data): array
    {
        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $subtotal += ((float) $item['quantity']) * ((float) $item['unit_price']);
        }

        $taxPercent = (float) ($data['tax_percent'] ?? 5);
        $discount = (float) ($data['discount'] ?? 0);
        $taxAmount = round(($subtotal - $discount) * ($taxPercent / 100), 2);
        $total = round($subtotal - $discount + $taxAmount, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'tax_percent' => $taxPercent,
            'tax_amount' => $taxAmount,
            'discount' => $discount,
            'total' => max($total, 0),
        ];
    }

    private function syncItems(Invoice $invoice, array $items): void
    {
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            $qty = (float) $item['quantity'];
            $price = (float) $item['unit_price'];

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'product_name' => $product->name ?? ($item['product_name'] ?? 'Product'),
                'quantity' => $qty,
                'unit_price' => $price,
                'line_total' => round($qty * $price, 2),
            ]);
        }
    }
}
