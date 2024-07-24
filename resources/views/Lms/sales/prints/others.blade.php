
<!doctype html>
<html style="height: 100%;box-sizing: border-box;">
<head>
    <meta charset="utf-8">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="{{ URL::asset('public/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <title>Head Office-Invoice Print</title>
</head>
<body>
<div class="page-footer">
    {!!App\Helpers\CommonHelper::invoice_footer()!!}
</div>
<style>
    .page-footer,.page-footer-space  {
        height: 34px;
    }

    .page-footer {
        position:absolute;
        bottom: 0;
        width: 99%;
    }
    .bg-dg{ background-color: silver}
    @media print{
        @page {margin: 0 0.5cm; margin-top: 20px;}
        html, body {
            margin: 0;
            padding: 0;
        }
        .col-md-12{ margin-top: 20px !important;}
        .page-footer{ display: block; position: absolute; width: 100% !important;}
        table td,th{font-size: 10px !important; -webkit-print-color-adjust: exact; }
    }
</style>
<div class="col-md-12" style="position: relative;min-height: 100%;height: 100%;">
    {!!App\Helpers\CommonHelper::invoice_header()!!}
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
            <td colspan="2"><h4 style="margin-bottom: 0px;margin-top: 0px;font-size: 14px;padding: 7px 0px;font-weight: 600;"><span style="border-bottom: double">OTHER SALE INVOICE</span></h4></td>
        </tr>
    </table>
    <table style="width: 100%; font-family: sans-serif;text-align: center;border-collapse: collapse; margin-top: 0px;font-size: 12px;" cellpadding="2">
        <thead>
        <tr style="border: 1px solid #000;">
            <th style="border: 1px solid #000;width:5%; text-align:center;">#</th>
            <th style="border: 1px solid #000; width:25%; text-align:center">Passenger Name</th>
            <th style="border: 1px solid #000; width:10%; text-align:center;">Details</th>
            <th style="border: 1px solid #000; width:10%; text-align:center;">Amount</th>
        </tr>
        </thead>
        <tbody>
        @php
            $net_total=0;
        @endphp
        @foreach($result as $item)
            @php
                $net_total+=$item->receiveable;
            @endphp
            <tr style="border: 1px solid #000;">
                <td style="border: 1px solid #000; text-align:center;">1</td>
                <td style="border: 1px solid #000;text-align:left">{{ $item->pax_name }}</td>
                <td style="border: 1px solid #000; text-align:center;">{{ $item->pkg_details }}</td>
                <td style="border: 1px solid #000; text-align:center;">{{ App\Helpers\Account::show_bal_format($item->receiveable) }}</td>
            </tr>
        @endforeach
        <tr style="border-top: 1px solid #000;">
            <td colspan="2" style="padding: 10px;text-align: left;">
                <u> {{ App\Helpers\Account::convertNumberToWord($net_total) }} </u></td>
            <th colspan="1" style="padding: 10px;text-align: right;"><u>Net Payable</u></th>
            <th style="text-align: center;"><u>{{ App\Helpers\Account::show_bal_format($net_total) }}</u></th>
        </tr>
        </tbody>
        <tfoot style="border: 0px;">
        <tr>
            <td colspan="12"><div class="page-footer-space"></div></td>
        </tr>
        </tfoot>
    </table><br>
    <table style="width: 100%; font-family: sans-serif;position: relative;margin-top: 40px;font-size: 12px;">
        <tr>
            <th colspan="3" style="padding: 10px;padding-left: 3px; text-align: left;">Notes:</th>
        </tr>
        <tr>
            <td colspan="3" style="padding: 3px;text-align: left;">1. In case of any kind of discripancy, please contact within 24 hours via phone or e-mail.</td>
        </tr>
    </table>
</div>
</body>
</html>
