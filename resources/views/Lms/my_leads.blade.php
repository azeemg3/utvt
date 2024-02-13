@extends('layout.master')
@section('mytitle', __('lms.my_leads'))
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Lms'];
        $breadcrumb[] = ['title' => __('lms.my_leads')];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    @include('Lms.modals.lead-details-modal')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="row">
                        <x-lead-box :lead="[$pending_leads, 'badge-secondary', 'Pending Leads','far fa-bookmark']" />
                        <x-lead-box :lead="[$takenover_leads, 'bg-primary', 'Takenover Leads','fas fa-sync-alt']" />
                        <x-lead-box :lead="[$inprocess_leads, 'bg-info', 'In Process Leads','fas fa-sync-alt fa-spin']" />
                        <x-lead-box :lead="[$successfull_leads, 'bg-success', 'Successfull Leads','far fa-thumbs-up']" />
                        <x-lead-box :lead="[$unSuccessfull_leads, 'bg-danger', 'UnSuccessfull Leads','far fa-thumbs-down']" />
                        <x-lead-box :lead="[$pending_leads, 'bg-yellow', 'All Leads','fas fa-shopping-cart']" />
                    </div>
                    <!--row-->
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-sm data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('lms.leadid') }}</th>
                                        <th>{{ __('lms.contact_name') }}</th>
                                        <th>{{ __('lms.mobile') }}</th>
                                        <th>{{ __('lms.spo_name') }}</th>
                                        <th>{{ __('lms.departure_date') }}</th>
                                        <th>{{ __('lms.arrival_date') }}</th>
                                        <th>{{ __('file.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
    @include('Lms.js.my_js')
@endsection
