@extends('layout.master')
@section('mytitle', __('lms.create_lead'))
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Lms'];
        $breadcrumb[] = ['title' => __('lms.create_lead')];
        $propertyTypes = [
            'Villa' => 'Villa',
            'Apartment' => 'Apartment',
            'Office' => 'Office',
            'Restaurant' => 'Restaurant',
            'Hotel / Hospitality' => 'Hotel / Hospitality',
            'Commercial' => 'Commercial',
        ];
        $emirates = [
            'Dubai' => 'Dubai',
            'Sharjah' => 'Sharjah',
            'Abu Dhabi' => 'Abu Dhabi',
            'Ajman' => 'Ajman',
            'Ras Al Khaimah' => 'Ras Al Khaimah',
            'Fujairah' => 'Fujairah',
            'Umm Al Quwain' => 'Umm Al Quwain',
        ];
        $selectedProperty = json_decode($data->sectors ?? '[]', true) ?: [];
        $selectedEmirates = json_decode($data->sectorss ?? '[]', true) ?: [];
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
                <div class="card-header">
                    <h3 class="card-title">Edit Lead — {{ optional($site_setting)->business_name ?? config('app.name') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form id="lead-form">
                            <input type="hidden" id="leadId" name="id" value="{{ $data->id }}">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Mobile / WhatsApp:<span class="text-danger">*</span></label>
                                        <input id="phone" name="mobile" class="form-control form-control-sm"
                                            type="tel" value="{{ $data->mobile }}" placeholder="e.g. +971558855071" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Secondary Mobile:</label>
                                        <input name="secondary_mobile" class="form-control form-control-sm"
                                            type="tel" value="{{ $data->secondary_mobile }}" placeholder="e.g. +971558855071" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Contact Name:<span class="text-danger">*</span></label>
                                        <input placeholder="Client / Contact Name" class="form-control form-control-sm"
                                            name="contact_name" type="text" value="{{ $data->contact_name }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input type="email" class="form-control form-control-sm" name="email"
                                            placeholder="client@email.com" value="{{ $data->email }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Project Address / Area:</label>
                                        <input placeholder="e.g. Al Sajja, Marina, Business Bay" class="form-control form-control-sm" value="{{ $data->cnic }}" name="cnic">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Assigned SPO:</label>
                                        <select class="form-control form-control-sm select2" name="spo"
                                            data-select2-id="1" tabindex="-1" aria-hidden="true">
                                            <option value="">Select SPO</option>
                                            {!! App\Models\User::dropdown($data->spo) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Preferred Consultation Date:</label>
                                        <input type="text" class="form-control form-control-sm date"
                                            name="service_date_from" placeholder="dd-mm-yyyy" value="{{ Helpers::date_format($data->service_date_from) }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Target Completion Date:</label>
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
                                        <label>Services Interested In</label>
                                        <select class="form-control form-control-sm select2" name="services[]"
                                            multiple="multiple">
                                            {!! App\Models\Lms\SService::dropdown($data->services) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Source Of Query<span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm select2" name="source_id">
                                            <option value="">Select Source</option>
                                            {!! App\Models\Lms\SourceQuery::dropdown($data->source_id) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Property Type:</label>
                                        <select class="form-control form-control-sm select2" name="sectors[]" multiple>
                                            @foreach($propertyTypes as $value => $label)
                                                <option value="{{ $value }}" @if(in_array($value, $selectedProperty)) selected @endif>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Project Emirate:</label>
                                        <select class="form-control form-control-sm select2" name="sectorss[]" multiple>
                                            @foreach($emirates as $value => $label)
                                                <option value="{{ $value }}" @if(in_array($value, $selectedEmirates)) selected @endif>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Budget Range (AED):</label>
                                        <select class="form-control form-control-sm" name="class_travel">
                                            <option value="5" @if($data->class_travel==5 || empty($data->class_travel)) selected @endif>Not specified</option>
                                            <option value="1" @if($data->class_travel==1) selected @endif>Under 25,000</option>
                                            <option value="2" @if($data->class_travel==2) selected @endif>25,000 – 75,000</option>
                                            <option value="3" @if($data->class_travel==3) selected @endif>75,000 – 150,000</option>
                                            <option value="4" @if($data->class_travel==4) selected @endif>Above 150,000</option>
                                        </select>
                                    </div>
                                </div>
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
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label>Project Requirements / Notes</label>
                                        <textarea rows="8" class="form-control textarea" placeholder="Describe project scope, room sizes, preferred finishes, site visit notes, etc." name="other_details">{{ $data->other_details }}</textarea>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-12 float-right">
                                    <button class="btn btn-success btn-flat float-right update_lead"
                                        type="button">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('Lms.exist-lead')
    @include('Lms.js_functions')
@endsection
