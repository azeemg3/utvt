<!doctype html>
<html style="height: 100%;box-sizing: border-box;">

<head>
    <meta charset="utf-8">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="{{ URL::asset('public/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <title>Head Office-Invoice Print</title>
</head>

<body>
    {{-- <div class="page-footer">
    {!!App\Helpers\CommonHelper::invoice_footer()!!}
</div> --}}
    <style>
        .page-footer,
        .page-footer-space {
            height: 34px;
        }

        h5 {
            margin: 0px !important;
            font-family: sans-serif;
        }

        .page-footer {
            position: absolute;
            bottom: 0;
            width: 99%;
        }

        .bg-dg {
            background-color: silver
        }

        @media print {
            @page {
                margin: 0 0.5cm;
                margin-top: 20px;
            }

            html,
            body {
                margin: 0;
                padding: 0;
            }

            .col-md-12 {
                margin-top: 20px !important;
            }

            .page-footer {
                display: block;
                position: absolute;
                width: 100% !important;
            }

            table td,
            th {
                font-size: 10px !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
    <div class="col-md-12" style="position: relative;min-height: 100%;height: 100%;">
        {!! App\Helpers\CommonHelper::invoice_header() !!}
        <table width="100%" style="font-family: sans-serif; margin-top: 20px;font-size: 12px">
            <tr>
                <td style="padding: 3px;width: 72%;text-align: left;"><b>To: {{ $to }}</b></td>
                <th>Invoice ID:&emsp;&emsp;{{ App\Helpers\CommonHelper::dsn($sale->id) }}</th>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <th>Invoice Date:&emsp;&emsp;{{ $sale->inv_date }}</th>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <th>Entered by:&emsp;&emsp;{{ $enter_by }}</th>
            </tr>
            <tr style="text-align: center;">
                <td colspan="2">
                    <h4 style="margin-bottom: 0px;margin-top: 0px;font-size: 14px;padding: 7px 0px;font-weight: 600;">
                        <span style="border-bottom: double">TOUR INVOICE</span></h4>
                </td>
            </tr>
        </table>
        <h5>Tickets Records:</h5>
        <table
            style="width: 100%; font-family: sans-serif;text-align: center;border-collapse: collapse; margin-top: 0px;font-size: 12px;"
            cellpadding="2">
            <thead>
                <tr style="border: 1px solid #000;">
                    <th style="border: 1px solid #000;width:5%; text-align:center;">#</th>
                    <th style="border: 1px solid #000; width:25%; text-align:center">Passenger Name</th>
                    <th style="border: 1px solid #000;width:20%; text-align:center;">Ticket No</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center;">Flight No.</th>
                    <th style="border: 1px solid #000; width:30%; text-align:center">Sector/Desc</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $net_total = 0;
                @endphp
                @foreach ($result as $item)
                    @php
                        $net_total += $item->receiveable;
                    @endphp
                    <tr style="border: 1px solid #000;">
                        <td style="border: 1px solid #000; text-align:center;">1</td>
                        <td style="border: 1px solid #000;text-align:left">{{ $item->pax_name }}</td>
                        <td style="border: 1px solid #000;text-align:center"><i class="fa fa-ticket"
                                aria-hidden="true"></i> {{ $item->ticket_no }}</td>
                        <td style="border: 1px solid #000; text-align:center;">
                        </td>
                        <td style="border: 1px solid #000;text-align:center">{{ strtoupper($item->sector) }}</td>
                        <td style="border: 1px solid #000; text-align:right;">{{ App\Helpers\Account::show_bal_format($item->receiveable) }}</td>
                    </tr>
                @endforeach
                <tr style="border-top: 1px solid #000;">
                    <td colspan="4" style="padding: 10px;text-align: left;"></td>
                    <th style="padding: 10px;text-align: right;"><u>Net Payable</u></th>
                    <th style="text-align: right;"><u>{{ App\Helpers\Account::show_bal_format($net_total) }}</u></th>
                </tr>
            </tbody>
        </table><br>
        <h5>Hotel Records:</h5>
        <table
            style="width: 100%; font-family: sans-serif;text-align: center;border-collapse: collapse; margin-top: 0px;font-size: 12px;"
            cellpadding="2">
            <thead>
                <tr style="border: 1px solid #000;">
                    <th style="border: 1px solid #000;width:5%; text-align:center;">#</th>
                    <th style="border: 1px solid #000; width:25%; text-align:center">Passenger Name</th>
                    <th style="border: 1px solid #000;width:15%; text-align:center;">Hotel</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center;">Conf No.</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center">Check in</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center">Check Out</th>
                    <th style="border: 1px solid #000; width:5%; text-align:center;">Night</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $hotelTotal = 0;
                @endphp
                @foreach ($hotels as $key => $hotel)
                    <tr style="border: 1px solid #000;">
                        <td style="border: 1px solid #000; text-align:center;">{{ $key + 1 }}</td>
                        <td style="border: 1px solid #000;text-align:left">{{ $hotel->pax_name }}</td>
                        <td style="border: 1px solid #000;text-align:center"><i class="fa fa-ticket"
                                aria-hidden="true"></i>{{ $hotel->hotel_name->name }}</td>
                        <td style="border: 1px solid #000; text-align:center;">{{ $hotel->confirmation }}</td>
                        <td style="border: 1px solid #000;text-align:center">{{ $hotel->checkin }}</td>
                        <td style="border: 1px solid #000;text-align:center">{{ $hotel->checkout }}</td>
                        <td style="border: 1px solid #000;text-align:center">{{ $hotel->nights }}</td>
                        <td style="border: 1px solid #000; text-align:right;">{{ App\Helpers\Account::show_bal_format($hotel->amount) }}</td>
                    </tr>
                    @php
                        $hotelTotal += $hotel->amount;
                    @endphp
                @endforeach
                <tr style="border-top: 1px solid #000;">
                    <td colspan="5" style="padding: 10px;text-align: left;">
                    <th colspan="2" style="padding: 10px;text-align: right;"><u>Net Payable</u></th>
                    <th style="text-align: right;"><u>{{ App\Helpers\Account::show_bal_format($hotelTotal) }}</u></th>
                </tr>
            </tbody>
            <tfoot style="border: 0px;">
                <tr>
                    <td colspan="12">
                        <div class="page-footer-space"></div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <h5>Visa Records:</h5>
        <table
            style="width: 100%; font-family: sans-serif;text-align: center;border-collapse: collapse; margin-top: 0px;font-size: 12px;"
            cellpadding="2">
            <thead>
                <tr style="border: 1px solid #000;">
                    <th style="border: 1px solid #000;width:5%; text-align:center;">#</th>
                    <th style="border: 1px solid #000; width:25%; text-align:center">Passenger Name</th>
                    <th style="border: 1px solid #000;width:15%; text-align:center;">Visa Country</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center;">Visa Type</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center">Visa No.</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $visa_total = 0;
                @endphp
                @foreach ($visa as $vkey => $vis)
                    <tr style="border: 1px solid #000;">
                        <td style="border: 1px solid #000; text-align:center;">{{ $vkey + 1 }}</td>
                        <td style="border: 1px solid #000;text-align:left">{{ strtoupper($vis->pax_name) }}</td>
                        <td style="border: 1px solid #000; text-align:center;">{{ $vis->country->name }}</td>
                        <td style="border: 1px solid #000; text-align:center;">
                            {{ App\Helpers\CommonHelper::get_visa_type($vis->visa_type) }}</td>
                        <td style="border: 1px solid #000; text-align:center;">{{ $vis->visa_no }}</td>
                        <td style="border: 1px solid #000; text-align:right;">
                            {{ App\Helpers\Account::show_bal_format($vis->amount) }}</td>
                    </tr>
                    @php
                        $visa_total += $vis->amount;
                    @endphp
                @endforeach
                <tr style="border-top: 1px solid #000;">
                    <td colspan="3" style="padding: 10px;text-align: left;"></td>
                    <th colspan="2" style="padding: 10px;text-align: right;"><u>Net Payable</u></th>
                    <th style="text-align: right;"><u>{{ App\Helpers\Account::show_bal_format($vis->amount) }}</u></th>
                </tr>
            </tbody>
        </table>
        <h5>Transport Records:</h5>
        <table
            style="width: 100%; font-family: sans-serif;text-align: center;border-collapse: collapse; margin-top: 0px;font-size: 12px;"
            cellpadding="2">
            <thead>
                <tr style="border: 1px solid #000;">
                    <th style="border: 1px solid #000;width:5%; text-align:center;">#</th>
                    <th style="border: 1px solid #000; width:25%; text-align:center">Passenger Name</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center;">Vehicle Type</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center;">From Date</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center;">To Date</th>
                    <th style="border: 1px solid #000; width:10%; text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $tr_total = 0;
                @endphp
                @foreach ($transports as $trkey => $transport)
                    <tr style="border: 1px solid #000;">
                        <td style="border: 1px solid #000; text-align:center;">{{ $trkey + 1 }}</td>
                        <td style="border: 1px solid #000;text-align:left">{{ strtoupper($transport->pax_name) }}</td>
                        <td style="border: 1px solid #000; text-align:center;">
                            {{ App\Helpers\CommonHelper::get_vehicle_type($transport->vehicle_type) }}</td>
                        <td style="border: 1px solid #000; text-align:center;">{{ $transport->from_date }}</td>
                        <td style="border: 1px solid #000; text-align:center;">{{ $transport->to_date }}</td>
                        <td style="border: 1px solid #000; text-align:center;">
                            {{ App\Helpers\Account::show_bal_format($transport->amount) }}</td>
                    </tr>
                    @php
                        $tr_total += $transport->amount;
                    @endphp
                @endforeach
                <tr style="border-top: 1px solid #000;">
                    <td colspan="3" style="padding: 10px;text-align: left;"></td>
                    <th colspan="2" style="padding: 10px;text-align: right;"><u>Net Payable</u></th>
                    <th style="text-align: right;"><u>{{ App\Helpers\Account::show_bal_format($tr_total) }}</u></th>
                </tr>
            </tbody>
            <tfoot style="border: 0px;">
                <tr>
                    <td colspan="12">
                        <div class="page-footer-space"></div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <h5>Other Records:</h5>
        <table
            style="width: 100%; font-family: sans-serif;text-align: center;border-collapse: collapse; margin-top: 0px;font-size: 12px;"
            cellpadding="2">
            <thead>
                <tr style="border: 1px solid #000;">
                    <th style="border: 1px solid #000;width:5%; text-align:center;">#</th>
                    <th style="border: 1px solid #000; width:25%; text-align:center">Passenger Name</th>
                    <th style="border: 1px solid #000; width:10%; text-align:center;">Details</th>
                    <th style="border: 1px solid #000; width:10%; text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $other_total = 0;
                @endphp
                @foreach ($others as $okey => $other)
                    <tr style="border: 1px solid #000;">
                        <td style="border: 1px solid #000; text-align:center;">{{ $okey + 1 }}</td>
                        <td style="border: 1px solid #000;text-align:left">{{ $other->pax_name }}</td>
                        <td style="border: 1px solid #000; text-align:center;">{{ $other->pkg_details }}</td>
                        <td style="border: 1px solid #000; text-align:right;">
                            {{ App\Helpers\Account::show_bal_format($other->amount) }}</td>
                    </tr>
                    @php
                        $other_total += $other->amount;
                    @endphp
                @endforeach
                <tr style="border-top: 1px solid #000;">
                    <td colspan="2" style="padding: 10px;text-align: left;"></td>
                    <th colspan="1" style="padding: 10px;text-align: right;"><u>Net Payable</u></th>
                    <th style="text-align: right;"><u>{{ App\Helpers\Account::show_bal_format($other_total) }}</u>
                    </th>
                </tr>
            </tbody>
            <tfoot style="border: 0px;">
                <tr>
                    <td colspan="12">
                        <div class="page-footer-space"></div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <h5 align="center" style="padding:5px;">Balance Summary</h5>
        <table class="new-table"
            style="width: 50%; font-family: sans-serif;text-align: center; border-collapse: collapse;" align="center">
            <thead>
                <tr style="border: 1px solid #000;">
                    <th style="border: 1px solid #000; padding: 2px;text-align:center">Particulars</th>
                    <th style="border: 1px solid #000; padding: 2px;text-align:center">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="text-align:left; padding:3px;"><strong>Invoices</strong></td>
                </tr>
                <tr>
                    <td style="padding:2px">Ticket Invoices</td>
                    <td style="padding:2px">{{ App\Helpers\Account::show_bal_format($net_total) }}</td>
                </tr>
                <tr>
                    <td style="padding:2px">Hotel Invoices</td>
                    <td style="padding:2px">{{ App\Helpers\Account::show_bal_format($hotelTotal) }}</td>
                </tr>
                <tr>
                    <td style="padding:2px">Visa Invoices</td>
                    <td style="padding:2px">{{ App\Helpers\Account::show_bal_format($visa_total) }}</td>
                </tr>
                <tr>
                    <td style="padding:2px">Transport Invoices</td>
                    <td style="padding:2px">{{ App\Helpers\Account::show_bal_format($tr_total) }}</td>
                </tr>
                            </tbody>
                <tfoot>
                  <tr style="border: 1px solid #000;">
                    <th style="padding: 2px;text-align: right;">Total:</th>
                    <th style="padding: 2px;text-align: center;border: 1px solid #000;">
                    {{App\Helpers\Account::show_bal_format($net_total+$hotelTotal+$tr_total+$visa_total+$other_total)}}
                    </th>
                  </tr>
                </tfoot>
            </tbody>
        </table>
        <table style="width: 100%; font-family: sans-serif;position: relative;margin-top: 40px;font-size: 12px;">
            <tr>
                <th colspan="3" style="padding: 10px;padding-left: 3px; text-align: left;">Notes:</th>
            </tr>
            <tr>
                <td colspan="3" style="padding: 3px;text-align: left;">1. In case of any kind of discripancy,
                    please contact within 24 hours via phone or e-mail.</td>
            </tr>
        </table>
    </div>
</body>

</html>
