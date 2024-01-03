@extends('layout.master')
@section('mytitle', __('lms.create_lead'))
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Lms'];
        $breadcrumb[] = ['title' => __('lms.create_lead')];
    @endphp
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <style>
        .iti--allow-dropdown input,
        .iti--allow-dropdown input[type=text],
        .iti--allow-dropdown input[type=tel],
        .iti--separate-dial-code input,
        .iti--separate-dial-code input[type=text],
        .iti--separate-dial-code input[type=tel] {
            padding-right: 40px !important;
        }
    </style>
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <form id="lead-form">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Mobile:<span class="text-danger">*</span></label>
                                        <input id="phone" name="mobile" class="form-control form-control-sm"
                                            type="tel" name="phone" value="+92" placeholder="e.g. +923244659501" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Contact Name:<span class="text-danger">*</span></label>
                                        <input placeholder="Contact Name" class="form-control form-control-sm"
                                            name="contact_name" type="text" value=""
                                            style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Email:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="email"
                                            placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>CNIC:</label>
                                        <input placeholder="CNIC" class="form-control form-control-sm" name="cnic">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>SPO:</label>
                                        <select class="form-control form-control-sm select2" name="spo"
                                            data-select2-id="1" tabindex="-1" aria-hidden="true">
                                            <option value="">Select Spo</option>
                                            {!! App\Models\User::dropdown() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Traveling Date From:</label>
                                        <input type="text" class="form-control form-control-sm date"
                                            name="travel_date_from" placeholder="dd-mm-yyyy" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Traveling Date To:</label>
                                        <input type="text" class="form-control form-control-sm date"
                                            name="travel_date_to" placeholder="dd-mm-yyyy" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Country:<span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm select2 country" id="country"
                                            name="CID">
                                            <option value="">Select Country</option>
                                            {!! App\Models\Country::dropdown() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>City:</label>
                                        <select class="form-control form-control-sm select2 city" id="cityList"
                                            name="CTID">
                                            <option value="">Select City</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Services</label>
                                        <select class="form-control form-control-sm select2" name="services[]" multiple="multiple">
                                            <option value="">Select Services</option>
                                            {!! App\Models\Lms\SService::dropdown() !!}
                                        </select>
                                    </div>
                                </div>
                                <!--end-column-->
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Sector:</label>
                                        <input type="text" class="form-control form-control-sm" name="sectors"
                                            placeholder="e.g. LHE-KHE">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Source Of Query<span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm select2" name="source_of_query">
                                            <option value="">Select Query</option>
                                            {!! App\Models\Lms\SourceQuery::dropdown() !!}
                                        </select>
                                    </div>
                                </div>
                                <!--end-column-->
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <textarea rows="20" class="form-control textarea" placeholder="Other Details" name="other_details"></textarea>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-12 float-right">
                                    <button class="btn btn-success btn-flat float-right create_lead" data-spo="1"
                                        type="button">Create &amp; Takeover</button>
                                    <button class="btn btn-primary btn-flat float-right create_lead" data-spo="2"
                                        type="button">Create for Others</button>
                                </div>
                                <!--end-column-->
                            </div>
                            <!--end-row-->
                        </form>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
    @include('Lms.exist-lead')
    @include('Lms.js_functions')

@endsection
