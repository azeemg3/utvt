@extends('layout.master')
@section('mytitle', __('lms.all_leads'))
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Lms'];
        $breadcrumb[] = ['title' => __('lms.all_leads')];
        $box0=$box1 = $box2 = $box3 = $box4 = $box5 = $box6 = $box7 = $box8 = $box9 = $box10 = $box11 = $box12 = $box13 = $box14 = $box15 = $box16 = $box17 = $box18 = $box19 = $box20 = 0;
    @endphp
    @foreach ($boxCounts as $boxID => $boxVal)
        @if ($boxVal->BOXID == 0)
            @php $box0=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 1)
        @php $box1=$boxVal->count; @endphp
    @endif
        @if ($boxVal->BOXID == 2)
            @php $box2=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 3)
            @php $box3=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 4)
            @php $box4=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 5)
            @php $box5=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 6)
            @php $box6=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 7)
            @php $box7=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 8)
            @php $box8=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 9)
            @php $box9=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 10)
            @php $box10=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 11)
            @php $box11=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 12)
            @php $box12=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 13)
            @php $box13=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 14)
            @php $box14=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID ==15)
            @php $box15=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 16)
            @php $box16=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 17)
            @php $box17=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 18)
            @php $box18=$boxVal->count; @endphp
        @endif
        @if ($boxVal->BOXID == 19)
            @php $box19=$boxVal->count; @endphp
        @endif
    @endforeach
    <x-content-header :breadcrumb="$breadcrumb" />
    @include('Lms.modals.lead-details-modal')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="row">
                        @foreach (Helpers::lead_boxes() as $key => $val)
                            @if ($key != 19 && $key != 18)
                                <x-lead-box :lead="[
                                    'icon' => 'bullhorn',
                                    'name' => $val,
                                    'key' => $key,
                                    'box0' => Helpers::leadId_fromat($box0),
                                    'box1' => Helpers::leadId_fromat($box1),
                                    'box2' => Helpers::leadId_fromat($box2),
                                    'box3' => Helpers::leadId_fromat($box3),
                                    'box4' => Helpers::leadId_fromat($box4),
                                    'box5' => Helpers::leadId_fromat($box5),
                                    'box6' => Helpers::leadId_fromat($box6),
                                    'box7' => Helpers::leadId_fromat($box7),
                                    'box8' => Helpers::leadId_fromat($box8),
                                    'box9' => Helpers::leadId_fromat($box9),
                                    'box10' => Helpers::leadId_fromat($box10),
                                    'box11' => Helpers::leadId_fromat($box11),
                                    'box12' => Helpers::leadId_fromat($box12),
                                    'box13' => Helpers::leadId_fromat($box13),
                                    'box14' => Helpers::leadId_fromat($box14),
                                    'box15' => Helpers::leadId_fromat($box15),
                                    'box16' => Helpers::leadId_fromat($box16),
                                    'box17' => Helpers::leadId_fromat($box17),
                                ]" />
                            @endif
                        @endforeach
                        <x-lead-box :lead="[
                            'icon' => 'bullhorn',
                            'name' => 'Closed Leads',
                            'key' => '18',
                            'box18' => Helpers::leadId_fromat($box18 + $box19),
                        ]" />
                        <x-lead-box :lead="[
                            'icon' => 'bullhorn',
                            'name' => 'All Leads',
                            'key' => '20',
                            'box20' => Helpers::leadId_fromat($all_leads),
                        ]" />
                    </div>
                    <!--row-->

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="Enter Mobile Number" id="lead_mobile" >
                                    </div>
                                </div>
                                <!--col-->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="Enter Lead Number" id="lead_number">
                                    </div>
                                </div>
                                <!--col-->
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button type="button" id="search_lead" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <!--col-->
                            </div>
                            <!--row-->
                            <table class="table table-sm data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('lms.leadid') }}</th>
                                        <th>{{ __('lms.contact_name') }}</th>
                                        <th>{{ __('lms.mobile') }}</th>
                                        <th>{{ __('lms.spo_name') }}</th>
                                        <th>{{ __('lms.created_at') }}</th>
                                        <th>{{ __('lms.status') }}</th>
                                        <th>Remarks</th>
                                        <th>{{ __('file.action') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
    @include('Lms.js.all_js')
    @include('Lms.modals.lead-remarks')
@endsection
