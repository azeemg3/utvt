@extends('layout.master')
@section('mytitle', __('lms.all_leads'))
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Lms'];
        $breadcrumb[] = ['title' => __('lms.all_leads')];
        $box1=$box2=$box3=$box4=$box5=$box6=$box7=$box8=$box9=$box10=$box11=$box12=$box13=$box14=$box15=$box16=0;
    @endphp
    @foreach ($boxCounts as $boxID=>$boxVal)
        @if($boxVal->BOXID==1)
            @php $box1=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==2)
            @php $box2=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==3)
            @php $box3=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==4)
            @php $box4=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==5)
            @php $box5=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==6)
            @php $box6=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==7)
            @php $box7=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==8)
            @php $box8=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==9)
            @php $box9=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==10)
            @php $box10=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==11)
            @php $box11=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==12)
            @php $box12=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==13)
            @php $box13=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==14)
            @php $box14=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==15)
            @php $box16=$boxVal->count; @endphp
        @endif
        @if($boxVal->BOXID==16)
            @php $box16=$boxVal->count; @endphp
        @endif
    @endforeach
    <x-content-header :breadcrumb="$breadcrumb" />
    @include('Lms.modals.lead-details-modal')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="row">
                        {{-- <x-lead-box :lead="[$pending_leads, 'badge-secondary', 'Pending Leads','far fa-bookmark']" />
                        <x-lead-box :lead="[$takenover_leads, 'bg-primary', 'Takenover Leads','fas fa-sync-alt']" />
                        <x-lead-box :lead="[$inprocess_leads, 'bg-info', 'In Process Leads','fas fa-sync-alt fa-spin']" />
                        <x-lead-box :lead="[$successfull_leads, 'bg-success', 'Successfull Leads','far fa-thumbs-up']" />
                        <x-lead-box :lead="[$unSuccessfull_leads, 'bg-danger', 'UnSuccessfull Leads','far fa-thumbs-down']" />
                        <x-lead-box :lead="[$all_leads, 'bg-yellow', 'All Leads','fas fa-shopping-cart']" /> --}}
                        @foreach (Helpers::lead_boxes() as $key=>$val)
                            <x-lead-box :lead="['icon'=>'bullhorn','name'=>$val,'key'=>$key,
                            'box1'=>Helpers::leadId_fromat($box1),
                            'box2'=>Helpers::leadId_fromat($box2),
                            'box3'=>Helpers::leadId_fromat($box3),
                            'box4'=>Helpers::leadId_fromat($box4),
                            'box5'=>Helpers::leadId_fromat($box5),
                            'box6'=>Helpers::leadId_fromat($box6),
                            'box7'=>Helpers::leadId_fromat($box7),
                            'box8'=>Helpers::leadId_fromat($box8),
                            'box9'=>Helpers::leadId_fromat($box9),
                            'box10'=>Helpers::leadId_fromat($box10),
                            'box11'=>Helpers::leadId_fromat($box11),
                            'box12'=>Helpers::leadId_fromat($box12),
                            'box13'=>Helpers::leadId_fromat($box13),
                            'box14'=>Helpers::leadId_fromat($box14),
                            'box15'=>Helpers::leadId_fromat($box15),
                            'box16'=>Helpers::leadId_fromat($box16),
                            ]" />
                        @endforeach
                    </div>
                    <!--row-->

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
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
@endsection
