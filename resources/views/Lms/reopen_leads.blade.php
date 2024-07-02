@extends('layout.master')
@section('mytitle', __('lms.my_leads'))

@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Lms'];
        $breadcrumb[] = ['title' => __('lms.reopen_lead')];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="Enter Mobile Number" id="lead_mobile">
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
                                        <button type="button" id="search_lead" class="btn btn-primary btn-sm"><i
                                                class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <!--col-->
                            </div>
                            <!--row-->
                            <div class="table-responsive">
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
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
    @include('Lms.js.reopen_js')
@endsection
