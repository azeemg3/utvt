@extends('layout.master')
@section('mytitle', 'Tax Invoice '.$invoice->serialDisplay())
@php
    $companyNameEn = optional($site_setting)->business_name ?? config('app.name');
    $companyNameAr = optional($site_setting)->business_other_details ?: '';
    $companyEmail = optional($site_setting)->business_email ?: '';
    $companyPhone = optional($site_setting)->business_phone ?: '';
    $companyAddress = optional($site_setting)->business_address ?: '';
    $companyTrn = optional($site_setting)->business_ntn ?: '';
    $logoUrl = !empty(optional($site_setting)->business_logo)
        ? asset($site_setting->business_logo)
        : asset('dist/img/AdminLTELogo.png');
    $taxPercent = (float) ($invoice->tax_percent ?: 5);
    $minRows = 16;
    $itemCount = $invoice->items->count();
    $emptyRows = max(0, $minRows - $itemCount);
    $netSubtotal = (float) $invoice->subtotal - (float) $invoice->discount;
@endphp
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-2 no-print">
                <div class="col-12">
                    <a href="{{ route('invoice.index') }}" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    <a href="{{ route('invoice.edit', $invoice->id) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>
                    <button type="button" class="btn btn-secondary btn-sm float-right" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>

            <div class="tax-invoice-sheet" id="tax-invoice-print">
                <div class="ti-header">
                    <div class="ti-brand-left">
                        <div class="ti-company-en">{{ $companyNameEn }}</div>
                    </div>
                    <div class="ti-brand-center">
                        <img src="{{ $logoUrl }}" alt="{{ $companyNameEn }}" class="ti-logo">
                    </div>
                    <div class="ti-brand-right">
                        <div class="ti-company-ar">{{ $companyNameAr }}</div>
                    </div>
                </div>

                <div class="ti-contact">
                    E-mail: {{ $companyEmail }}, {{ $companyAddress }}, Tel: {{ $companyPhone }}
                </div>

                <div class="ti-title-bar">
                    <span>TAX INVOICE</span>
                    <span class="ti-title-ar">فاتورة ضريبية</span>
                    <span class="ti-trn">TRN: {{ $companyTrn }}</span>
                </div>

                <div class="ti-meta">
                    <div class="ti-meta-left">
                        S. No. <strong class="ti-serial">{{ $invoice->serialDisplay() }}</strong>
                    </div>
                    <div class="ti-meta-right">
                        Date: <strong>{{ optional($invoice->invoice_date)->format('d-m-Y') }}</strong>
                        <span class="ti-ar-label">تاريخ</span>
                    </div>
                </div>

                <div class="ti-customer">
                    <div class="ti-customer-row">
                        <span class="ti-label">Mr./M/s.</span>
                        <span class="ti-value">{{ $invoice->customer_name }}</span>
                        <span class="ti-ar-label">السيد / السادة</span>
                    </div>
                    <div class="ti-customer-row">
                        <span class="ti-label">Address</span>
                        <span class="ti-value">{{ $invoice->customer_address ?: '—' }}</span>
                        <span class="ti-cust-trn">
                            Cust. TRN: <strong>{{ $invoice->customer_trn ?: '................' }}</strong>
                        </span>
                    </div>
                </div>

                <table class="ti-items">
                    <thead>
                        <tr>
                            <th class="col-sno">
                                <div class="th-ar">الرقم</div>
                                <div>S.No.</div>
                            </th>
                            <th class="col-desc">
                                <div class="th-ar">التفاصيل المنتج</div>
                                <div>Product Description</div>
                            </th>
                            <th class="col-qty">
                                <div class="th-ar">الكمية</div>
                                <div>Qty.</div>
                            </th>
                            <th class="col-rate">
                                <div class="th-ar">السعر</div>
                                <div>Rate (AED)</div>
                            </th>
                            <th class="col-amt">
                                <div class="th-ar">المبلغ بدون ضريبة</div>
                                <div>Amount Without VAT (AED)</div>
                            </th>
                            <th class="col-vat">
                                <div class="th-ar">الضريبة ٥ ٪</div>
                                <div>VAT {{ number_format($taxPercent, 0) }}%</div>
                            </th>
                            <th class="col-total">
                                <div class="th-ar">المبلغ مع ضريبة</div>
                                <div>Amount With VAT (AED)</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $index => $item)
                            @php
                                $lineExVat = (float) $item->quantity * (float) $item->unit_price;
                                $lineVat = round($lineExVat * ($taxPercent / 100), 2);
                                $lineIncVat = $lineExVat + $lineVat;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-center">{{ rtrim(rtrim(number_format($item->quantity, 2, '.', ''), '0'), '.') }}</td>
                                <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-right">{{ number_format($lineExVat, 2) }}</td>
                                <td class="text-right">{{ number_format($lineVat, 2) }}</td>
                                <td class="text-right">{{ number_format($lineIncVat, 2) }}</td>
                            </tr>
                        @endforeach
                        @for($i = 0; $i < $emptyRows; $i++)
                            <tr class="empty-row">
                                <td>&nbsp;</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr class="ti-total-words-row">
                            <td colspan="4" class="ti-words-cell">
                                <strong>Total Dhs.</strong>
                                {{ Helpers::amount_in_words($invoice->total) }}
                                <span class="ti-ar-label">المجموع درهم</span>
                            </td>
                            <td colspan="3" class="p-0">
                                <table class="ti-summary">
                                    <tr>
                                        <td>Subtotal <span class="ti-ar-inline">مجموع جزئي</span></td>
                                        <td class="text-right">{{ number_format($netSubtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>VAT {{ number_format($taxPercent, 0) }}% <span class="ti-ar-inline">الضريبة ٥ ٪</span></td>
                                        <td class="text-right">{{ number_format($invoice->tax_amount, 2) }}</td>
                                    </tr>
                                    <tr class="ti-grand">
                                        <td>Total AED <span class="ti-ar-inline">الإجمالي بالدرهم</span></td>
                                        <td class="text-right">{{ number_format($invoice->total, 2) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="ti-footer">
                    <div class="ti-footer-left">
                        <p>Received the above goods in good order &amp; condition.</p>
                        <p class="ti-sign-line">Customer's Signature ...............................</p>
                    </div>
                    <div class="ti-footer-right">
                        <p class="ti-sign-line">For {{ $companyNameEn }} ...............................</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<style>
    .tax-invoice-sheet {
        --ti-ink: #16325c;
        --ti-line: #16325c;
        --ti-bg: #e2c8a4;
        --ti-bg-soft: #e8d0b0;
        max-width: 920px;
        margin: 0 auto 24px;
        background: var(--ti-bg) !important;
        color: var(--ti-ink);
        border: 1px solid #c9a97a;
        padding: 18px 22px 24px;
        font-family: "Times New Roman", Times, serif;
        font-size: 13px;
    }
    .tax-invoice-sheet *,
    .tax-invoice-sheet table,
    .tax-invoice-sheet thead,
    .tax-invoice-sheet tbody,
    .tax-invoice-sheet tfoot,
    .tax-invoice-sheet tr,
    .tax-invoice-sheet th,
    .tax-invoice-sheet td {
        background-color: transparent;
        background-image: none;
    }
    .tax-invoice-sheet,
    .tax-invoice-sheet .ti-items,
    .tax-invoice-sheet .ti-items th,
    .tax-invoice-sheet .ti-items td,
    .tax-invoice-sheet .ti-summary,
    .tax-invoice-sheet .ti-summary td,
    .tax-invoice-sheet .ti-words-cell,
    .tax-invoice-sheet .ti-logo {
        background-color: #e2c8a4 !important;
    }
    .tax-invoice-sheet .ti-items thead th {
        background-color: #e8d0b0 !important;
    }
    .tax-invoice-sheet .ti-title-bar {
        background: #16325c !important;
        color: #fff !important;
    }
    .ti-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 4px;
    }
    .ti-brand-left, .ti-brand-right {
        flex: 1;
    }
    .ti-brand-right {
        text-align: right;
        direction: rtl;
    }
    .ti-company-en {
        font-size: 22px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #16325c;
        line-height: 1.1;
    }
    .ti-company-en-sub {
        font-size: 15px;
        font-weight: 600;
        color: #16325c;
    }
    .ti-company-ar {
        font-size: 20px;
        font-weight: 700;
        color: #16325c;
        line-height: 1.35;
    }
    .ti-brand-center {
        flex: 0 0 110px;
        text-align: center;
    }
    .ti-logo {
        width: 96px;
        height: 96px;
        object-fit: contain;
        border-radius: 50%;
        background: var(--ti-bg) !important;
    }
    .ti-contact {
        text-align: center;
        font-size: 12px;
        margin: 4px 0 10px;
        color: #16325c;
    }
    .ti-title-bar {
        background: #16325c;
        color: #fff;
        text-align: center;
        padding: 7px 10px;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }
    .ti-title-ar {
        font-size: 18px;
    }
    .ti-trn {
        font-size: 14px;
        font-weight: 600;
    }
    .ti-meta {
        display: flex;
        justify-content: space-between;
        margin: 10px 0 6px;
        font-size: 14px;
    }
    .ti-serial {
        color: #c0392b;
        font-size: 16px;
    }
    .ti-customer {
        margin-bottom: 8px;
    }
    .ti-customer-row {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 6px;
        border-bottom: 1px dotted #16325c;
        padding-bottom: 3px;
    }
    .ti-label {
        white-space: nowrap;
        font-weight: 600;
    }
    .ti-value {
        flex: 1;
        min-height: 18px;
        font-weight: 600;
    }
    .ti-ar-label {
        white-space: nowrap;
        direction: rtl;
        font-size: 13px;
    }
    .ti-cust-trn {
        white-space: nowrap;
        margin-left: auto;
    }
    .ti-items {
        width: 100%;
        border-collapse: collapse;
        background: var(--ti-bg) !important;
    }
    .ti-items th, .ti-items td {
        border: 1px solid var(--ti-line);
        padding: 4px 5px;
        vertical-align: middle;
        background: var(--ti-bg) !important;
        color: var(--ti-ink);
    }
    .ti-items thead th {
        text-align: center;
        font-size: 11px;
        background: var(--ti-bg-soft) !important;
        color: var(--ti-ink);
        font-weight: 700;
    }
    .th-ar {
        font-size: 11px;
        margin-bottom: 2px;
    }
    .col-sno { width: 6%; }
    .col-desc { width: 32%; }
    .col-qty { width: 8%; }
    .col-rate { width: 11%; }
    .col-amt { width: 15%; }
    .col-vat { width: 12%; }
    .col-total { width: 16%; }
    .ti-items tbody td {
        height: 22px;
        font-size: 12px;
    }
    .ti-items .empty-row td {
        height: 22px;
    }
    .ti-words-cell {
        text-align: left;
        font-size: 12px;
        vertical-align: top !important;
        padding: 8px !important;
        background: var(--ti-bg) !important;
    }
    .ti-summary {
        width: 100%;
        border-collapse: collapse;
        background: var(--ti-bg) !important;
    }
    .ti-summary td {
        border: 1px solid var(--ti-line);
        padding: 5px 6px;
        font-size: 12px;
        font-weight: 600;
        background: var(--ti-bg) !important;
        color: var(--ti-ink);
    }
    .ti-summary tr.ti-grand td {
        font-size: 13px;
        font-weight: 700;
    }
    .ti-ar-inline {
        float: right;
        font-weight: 500;
    }
    .ti-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 18px;
        gap: 20px;
        font-size: 13px;
    }
    .ti-footer-left, .ti-footer-right {
        flex: 1;
    }
    .ti-footer-right {
        text-align: right;
        padding-top: 28px;
    }
    .ti-sign-line {
        margin-top: 18px;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }

    @media print {
        @page {
            size: A4;
            margin: 0;
        }
        html, body {
            background: #e2c8a4 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        .main-sidebar, .main-header, .main-footer, .content-header,
        .no-print, .navbar, .control-sidebar, #loader {
            display: none !important;
        }
        .content-wrapper,
        .content,
        .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
            background: #e2c8a4 !important;
            width: 100% !important;
        }
        .tax-invoice-sheet {
            max-width: 100%;
            min-height: 100vh;
            margin: 0;
            border: none;
            box-shadow: none;
            background: #e2c8a4 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        .tax-invoice-sheet th,
        .tax-invoice-sheet td,
        .ti-summary td,
        .ti-items,
        .ti-logo {
            background: #e2c8a4 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .ti-title-bar {
            background: #16325c !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endpush
