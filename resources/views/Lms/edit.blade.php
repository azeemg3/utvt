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
        <style>
            .select2-container--default .select2-selection--multiple .select2-selection__choice{
                background-color:#181212 !important;
            }
        </style>
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <form id="lead-form">
                            <input type="hidden" id="leadId" name="id" value="{{ $data->id }}">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Mobile:<span class="text-danger">*</span></label>
                                        <input id="phone" name="mobile" class="form-control form-control-sm"
                                            type="tel" name="phone" value="{{ $data->mobile }}" placeholder="e.g. +923244659501" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Secondary Mobile:</label>
                                        <input name="secondary_mobile" class="form-control form-control-sm"
                                            type="tel" placeholder="e.g. +923244659501" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Contact Name:<span class="text-danger">*</span></label>
                                        <input placeholder="Contact Name" class="form-control form-control-sm"
                                            name="contact_name" type="text" value="{{ $data->contact_name }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Email:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="email"
                                            placeholder="Email" value="{{ $data->email }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>CNIC:</label>
                                        <input placeholder="CNIC" class="form-control form-control-sm" value="{{ $data->cnic }}" name="cnic">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>SPO:</label>
                                        <select class="form-control form-control-sm select2" name="spo"
                                            data-select2-id="1" tabindex="-1" aria-hidden="true">
                                            <option value="">Select Spo</option>
                                            {!! App\Models\User::dropdown($data->spo) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Traveling Date From:</label>
                                        <input type="text" class="form-control form-control-sm date"
                                            name="service_date_from" placeholder="dd-mm-yyyy" value="{{ Helpers::date_format($data->service_date_from) }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Traveling Date To:</label>
                                        <input type="text" class="form-control form-control-sm date"
                                            name="service_date_to" placeholder="dd-mm-yyyy" value="{{ Helpers::date_format($data->service_date_to) }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Country:<span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm select2 country" id="country"
                                            name="CID">
                                            <option value="">Select Country</option>
                                            {!! App\Models\Country::dropdown($data->CID) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>City:</label>
                                        <select class="form-control form-control-sm select2 city" id="cityList"
                                            name="CTID">
                                            <option value="">Select City</option>
                                            {!! App\Models\City::dropdown($data->CTID) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Services</label>
                                        <select class="form-control form-control-sm select2" name="services[]"
                                            multiple="multiple">
                                            {!! App\Models\Lms\SService::dropdown($data->services) !!}
                                        </select>
                                    </div>
                                </div>
                                <!--end-column-->
                                <div class="col-xs-12 col-sm-6 col-md-1">
                                    <div class="form-group">
                                        <label>Adult</label>
                                        <input type="text" name="adult" value="{{ $data->adult }}" class="form-control form-control-sm"
                                            placeholder="0">
                                    </div>
                                </div>
                                <!--end-column-->
                                <div class="col-xs-12 col-sm-6 col-md-1">
                                    <div class="form-group">
                                        <label>Child</label>
                                        <input type="text" name="child" value="{{ $data->child }}" class="form-control form-control-sm"
                                            placeholder="0">
                                    </div>
                                </div>
                                <!--end-column-->
                                <div class="col-xs-12 col-sm-6 col-md-1">
                                    <div class="form-group">
                                        <label>Infant</label>
                                        <input type="text" name="infant" value="{{ $data->infant }}" class="form-control form-control-sm"
                                            placeholder="0">
                                    </div>
                                </div>
                                <!--end-column-->
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Source Of Query<span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm select2" name="source_id">
                                            <option value="">Select Query</option>
                                            {!! App\Models\Lms\SourceQuery::dropdown($data->source_id) !!}
                                        </select>
                                    </div>
                                </div>
                                <!--end-column-->
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Sector:</label>
                                        <select class="form-control form-control-sm select2" name="sectors[]" multiple>
                                            {!! App\Models\Sector::dropdown($data->sectors) !!}
                                        </select>
                                    </div>
                                </div>
                                <!--end-columns-->
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Sector:</label>
                                        <select class="form-control form-control-sm select2" name="sectorss[]" multiple>
                                            {!! App\Models\Sector::dropdown() !!}
                                        </select>
                                    </div>
                                </div>
                                <!--end-columns-->
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Airline:</label>
                                        <select class="form-control form-control-sm select2" name="airline[]" multiple>
                                            {!! App\Models\Airline::dropdown($data->airlines) !!}
                                        </select>
                                    </div>
                                </div>
                                <!--end-columns-->
                                <div class="col-xs-12 col-sm-6 col-md-1">
                                    <br>
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio1"
                                                name="priority" @if($data->priority==1) checked @endif value="1">
                                            <label for="customRadio1" class="custom-control-label">Low</label>
                                        </div>
                                    </div>
                                </div>
                                <!--end-column-->
                                <div class="col-xs-12 col-sm-6 col-md-1">
                                    <br>
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio2"
                                                name="priority" value="2" @if($data->priority==2) checked @endif>
                                            <label for="customRadio2" class="custom-control-label">Medium</label>
                                        </div>
                                    </div>
                                </div>
                                <!--end-column-->
                                <div class="col-xs-12 col-sm-6 col-md-1">
                                    <br>
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio3"
                                                name="priority" value="3" @if($data->priority==3) checked @endif>
                                            <label for="customRadio3" class="custom-control-label">High</label>
                                        </div>
                                    </div>
                                </div>
                                <!--end-column-->
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Class Of Travel:</label>
                                        <select class="form-control form-control-sm" name="class_travel">
                                            <option value="1">Economy</option>
                                            <option value="2">Business</option>
                                        </select>
                                    </div>
                                </div>
                                <!--end-columns-->
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <textarea rows="20" class="form-control textarea" placeholder="Other Details" name="other_details">{{ $data->other_details }}</textarea>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-12 float-right">
                                    <button class="btn btn-success btn-flat float-right update_lead"
                                        type="button">Update</button>
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
