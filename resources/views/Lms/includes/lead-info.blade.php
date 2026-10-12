<style>
    table{
        font-size:16px;
    }
</style>
@php
    $budgetLabels = [
        1 => 'Under AED 25,000',
        2 => 'AED 25,000 – 75,000',
        3 => 'AED 75,000 – 150,000',
        4 => 'Above AED 150,000',
        5 => 'Not specified',
    ];
@endphp
<div class="col-md-12">
    <div class="invoice p-3 mb-3">
        <!-- title row -->
        <div class="row">
            <div class="col-12">
                <h4>
                    <i class="fas fa-user"></i> {{  $data->contact_name }}
                    <small class="float-right"><strong>Created Date</strong>@if(!@empty($data->reopen_at)) {{ Helpers::date_format($data->reopen_at) }}@else {{ Helpers::date_format($data->created_at) }} @endif</small>
                </h4>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                Contact Details:
                <address>
                    <table>
                        <thead>
                            <tr>
                                <th> Mobile / WhatsApp:</th><td><strong>{{ $data->mobile }}</strong></td>
                            </tr>
                            <tr>
                                <th>Secondary  Mobile:</th><td><strong>{{ $data->secondary_mobile??"N/A" }}</strong></td>
                            </tr>
                            <tr>
                                <th>Email:</th><td>{{ $data->email }}</td>
                            </tr>
                            <tr>
                                <th>Project Address:</th><td>{{ $data->cnic ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Location:</th>
                                <td>{{ optional($data->city)->name }} {{ optional($data->country)->name }}</td>
                            </tr>
                            <tr>
                                <th>Customer Type:</th>
                                <td> @if(!empty($data->reopen_at)) Repeat @else Fresh @endif</td>
                            </tr>
                        </thead>
                    </table>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                Project Details:
                <address>
                    <table>
                        <tr>
                            <th>Consultation Date:</th>
                            <td>{{ Helpers::date_format($data->service_date_from) }}</td>
                        </tr>
                        <tr>
                            <th>Target Completion:</th>
                            <td>{{ Helpers::date_format($data->service_date_to) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('settings.source_of_query') }}:</th>
                            <td>{{ optional($data->source)->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('settings.services') }}:</th>
                            <td>{!! Helpers::lead_services(json_decode($data->services) ?: []) !!}</td>
                        </tr>
                        <tr>
                            <th>Property Type:</th>
                            <td>{!! Helpers::sectors(json_decode($data->sectors) ?: []) !!}</td>
                        </tr>
                        <tr>
                            <th>Project Emirate:</th>
                            <td>{!! Helpers::sectors(json_decode($data->sectorss) ?: []) !!}</td>
                        </tr>
                        <tr>
                            <th>Budget Range:</th>
                            <td>{{ $budgetLabels[$data->class_travel] ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <table>
                    <tr>
                        <th>LeadID:</th>
                        <td>{{  $data->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('lms.status') }}:</th>
                        <td>{!! Helpers::lead_status_badge($data->status) !!}</td>
                    </tr>
                    <tr>
                        <th>{{ __('lms.created_by') }}:</th>
                        <td>{{ optional($data->createdBy)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('lms.takenover_by') }}:</th>
                        <td>{{ optional($data->leadSpo)->name }}</td>
                    </tr>
                    <tr>
                        <th>Transfer To:</th>
                        <td>
                            <select class="form-control form-control-sm select2" id="lead-transfer" data-leadid="{{ $data->id }}">
                                <option value="">Select Spo</option>
                                {!! App\Models\User::dropdown() !!}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Recent Reminer:</th>
                        <td>
                            @if(isset($recent_reminder))
                            <span class="badge badge-pill bg-primary lead-reminder-update" data-id="{{$recent_reminder->id}}">
                            {{Helpers::date_format($recent_reminder->reminder_date)}} {{$recent_reminder->reminder_time}}
                            </span>
                            @else
                            N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Priority:</th>
                        <td>{!! Helpers::lead_priority($data->priority)!!}</td>
                    </tr>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
</div>
<!--col-->
