<style>
    table{
        font-size:16px;
    }
</style>
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
                                <th> Mobile:</th><td><strong>{{ $data->mobile }}</strong></td>
                            </tr>
                            <tr>
                                <th>Secondary  Mobile:</th><td><strong>{{ $data->secondary_mobile??"N/A" }}</strong></td>
                            </tr>
                            <tr>
                                <th>Email:</th><td>{{ $data->email }}</td>
                            </tr>
                            <tr>
                                <th>CNIC:</th><td>{{ $data->cnic }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $data->city->name }} {{ $data->country->name }}</td>
                            </tr>
                            <tr>
                                <th>Customer Type:</th>
                                <td> @if(!empty($data->reopen_at)) Repeat @else Fresh @endif</td>
                            </tr>
                            <tr>
                                <th>PAX Details:</th>
                                <td>Adult:{{$data->adult??0}}, Child:{{$data->child??0}}, Infant: {{$data->infant??0}}</td>
                            </tr>
                        </thead>
                    </table>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                Services Details:
                <address>
                    <table>
                        <tr>
                            <th>{{ __('lms.service_date_from') }}:</th>
                            <td>{{ Helpers::date_format($data->service_date_from) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('lms.service_date_to') }}:</th>
                            <td>{{ Helpers::date_format($data->service_date_to) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('settings.source_of_query') }}:</th>
                            <td>{{ $data->source->name??"" }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('settings.services') }}:</th>
                            <td>{!! Helpers::lead_services(json_decode($data->services)) !!}</td>
                        </tr>
                        <tr>
                            <th>Airlines:</th>
                            <td>{!! Helpers::airlines(json_decode($data->airlines)) !!}</td>
                        </tr>
                        <tr>
                            <th>{{ __('settings.sector') }}:</th>
                            <td>{!! Helpers::sectors(json_decode($data->sectors)) !!}-{!! Helpers::sectors(json_decode($data->sectorss)) !!}</td>
                        </tr>
                        <tr>
                            <th>{{ __('settings.route') }}:</th>
                            <td>{{ ($data->service_date_to=="" || $data->service_date_to=="1970-01-01" ?"One Way":"Return") }}</td>
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
                        <td>{!! Helpers::lead_status_badge($data->BOXID) !!}</td>
                    </tr>
                    <tr>
                        <th>{{ __('lms.created_by') }}:</th>
                        <td>{{ $data->createdBy->name??"" }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('lms.takenover_by') }}:</th>
                        <td>{{ $data->leadSpo->name??"" }}</td>
                    </tr>
                    {{-- @if($data->status!=5)
                    <tr>
                        <th>Change Manual Status:</th>
                        <td>
                            <select class="form-control form-control-sm" id="manual-status">
                                <option valiue="">Select Status</option>
                                <option value="3">In Process</option>
                                <option value="4">Successfull</option>
                                <option value="5">UnSuccessfull</option>
                            </select>
                        </td>
                    </tr>
                    @endif --}}
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
                    <tr>
                        <th>Class of Travel:</th>
                        <td>{{($data->class_travel==1?"Economy":"Business")}}</td>
                    </tr>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
</div>
<!--col-->
