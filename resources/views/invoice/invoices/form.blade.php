@extends('layout.master')
@php
    $existingItems = $invoice ? $invoice->items : collect();
@endphp
@section('mytitle', $mode === 'edit' ? 'Edit Invoice' : 'Create Invoice')
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Invoice'];
        $breadcrumb[] = ['title' => $mode === 'edit' ? 'Edit Invoice' : 'Create Invoice'];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <form id="invoice-form"
                  data-action="{{ $mode === 'edit' ? route('invoice.update', $invoice->id) : route('invoice.store') }}"
                  data-method="{{ $mode === 'edit' ? 'PUT' : 'POST' }}">
                @csrf
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{ $mode === 'edit' ? 'Edit Invoice #'.$invoice->invoice_number : 'New Invoice' }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer Name <x-text-danger /></label>
                                    <input type="text" name="customer_name" class="form-control form-control-sm"
                                           value="{{ old('customer_name', $invoice->customer_name ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer Email</label>
                                    <input type="email" name="customer_email" class="form-control form-control-sm"
                                           value="{{ old('customer_email', $invoice->customer_email ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer Phone</label>
                                    <input type="text" name="customer_phone" class="form-control form-control-sm"
                                           value="{{ old('customer_phone', $invoice->customer_phone ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Customer Address</label>
                                    <textarea name="customer_address" class="form-control form-control-sm" rows="2">{{ old('customer_address', $invoice->customer_address ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Customer TRN</label>
                                    <input type="text" name="customer_trn" class="form-control form-control-sm"
                                           value="{{ old('customer_trn', $invoice->customer_trn ?? '') }}" placeholder="Cust. TRN">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Invoice Date <x-text-danger /></label>
                                    <input type="date" name="invoice_date" class="form-control form-control-sm"
                                           value="{{ old('invoice_date', optional(optional($invoice)->invoice_date)->format('Y-m-d') ?: date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Due Date</label>
                                    <input type="date" name="due_date" class="form-control form-control-sm"
                                           value="{{ old('due_date', optional(optional($invoice)->due_date)->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status <x-text-danger /></label>
                                    @php $status = old('status', $invoice->status ?? 'unpaid'); @endphp
                                    <select name="status" class="form-control form-control-sm" required>
                                        <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="unpaid" {{ $status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Products</h5>
                            <button type="button" class="btn btn-sm btn-success" id="add-line">
                                <i class="fas fa-plus"></i> Add Product
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="items-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:40%">Product</th>
                                        <th style="width:15%">Qty</th>
                                        <th style="width:20%">Unit Price</th>
                                        <th style="width:20%">Line Total</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="items-body">
                                    @forelse($existingItems as $i => $item)
                                        <tr class="item-row">
                                            <td>
                                                <select name="items[{{ $i }}][product_id]" class="form-control form-control-sm product-select" required>
                                                    <option value="">Select Product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}"
                                                                data-price="{{ $product->price }}"
                                                                {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                            {{ $product->name }} ({{ number_format($product->price, 2) }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0.01" name="items[{{ $i }}][quantity]"
                                                       class="form-control form-control-sm qty-input" value="{{ $item->quantity }}" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0" name="items[{{ $i }}][unit_price]"
                                                       class="form-control form-control-sm price-input" value="{{ $item->unit_price }}" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm line-total" value="{{ number_format($item->line_total, 2, '.', '') }}" readonly>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-xs btn-danger remove-line"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-md-4">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Subtotal</th>
                                        <td class="text-right" id="subtotal-display">0.00</td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Discount
                                            <input type="number" step="0.01" min="0" name="discount" id="discount"
                                                   class="form-control form-control-sm mt-1"
                                                   value="{{ old('discount', $invoice->discount ?? 0) }}">
                                        </th>
                                        <td class="text-right align-middle" id="discount-display">0.00</td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Tax %
                                            <input type="number" step="0.01" min="0" max="100" name="tax_percent" id="tax_percent"
                                                   class="form-control form-control-sm mt-1"
                                                   value="{{ old('tax_percent', $invoice->tax_percent ?? 5) }}">
                                        </th>
                                        <td class="text-right align-middle" id="tax-display">0.00</td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td class="text-right font-weight-bold" id="total-display">0.00</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control form-control-sm" rows="2">{{ old('notes', $invoice->notes ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('invoice.index') }}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-primary float-right" id="save-invoice">
                            <i class="fas fa-save"></i> Save Invoice
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
@include('invoice.invoices.form_js')
@endpush
