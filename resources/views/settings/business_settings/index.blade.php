@extends('layout.master')
@section('mytitle', 'Business Settings')
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Dashboard'];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <form id="form" action="{{ route('business.store') }}">
                    <input type="hidden" name="id" value="{{ $businessSetting->id ??'' }}"
                    <div class="card-body" style="height: 350px">
                        <div class="row">
                            <div class="col-5 col-sm-3">
                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist"
                                    aria-orientation="vertical">
                                    <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill"
                                        href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home"
                                        aria-selected="false">Company Details</a>
                                    <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill"
                                        href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile"
                                        aria-selected="false">Contact Details</a>
                                    <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill"
                                        href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages"
                                        aria-selected="false">SMTP Settings</a>
                                    <a class="nav-link" id="vert-tabs-settings-tab" data-toggle="pill"
                                        href="#vert-tabs-settings" role="tab" aria-controls="vert-tabs-settings"
                                        aria-selected="true">API'S Settings</a>
                                </div>
                            </div>
                            <div class="col-7 col-sm-9">
                                <div class="tab-content" id="vert-tabs-tabContent">
                                    @include('settings.business_settings.includes.business-settings')
                                    @include('settings.business_settings.includes.business-contact')
                                    @include('settings.business_settings.includes.business-smtp')
                                    <div class="tab-pane fade" id="vert-tabs-settings" role="tabpanel"
                                        aria-labelledby="vert-tabs-settings-tab">

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                    <div class="col-md-12 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm float-right">Save</button>
                    </div>
                    <br><br>
                </form>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
