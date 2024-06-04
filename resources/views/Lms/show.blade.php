@extends('layout.master')
@section('mytitle', __('lms.all_leads'))
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Lms'];
        $breadcrumb[] = ['title' => __('lms.all_leads')];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            @if (Session::has('message'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                    {{ Session::get('message') }}
                                </div>
                        </div>
                        @endif
                        @include('Lms.includes.lead-info')
                    </div>
                    <!--row-->
                </div>
                <!--card-header-->
                {{-- @if (($data->status != 5 && $data->status != 1) || Auth::user()->role->name === 'Admin') --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#recent-conversation"
                                                data-toggle="tab">
                                                <i class="far fa-hourglass"></i> Client Conversation</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#timeline"
                                                data-toggle="tab"><i class="far fa-calendar-alt"></i> Timeline</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#client-conversation"
                                                onclick="get_lead_conversation({{ $data->id }})" data-toggle="tab">
                                                <i class="fas fa-comment"></i> Show
                                                Client Conversation</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#lead-reminders" data-toggle="tab">
                                            <i class="fa fa-bell"></i> {{__('lms.lead_reminder')}}</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#whatsapp-conversation"
                                                onclick="get_lead_conversation({{ $data->id }})" data-toggle="tab"><i
                                                    class="fab fa-whatsapp bg-success"></i> What's App Conversation</a></li>
                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        @include('Lms.includes.recent-conversation')
                                        @include('Lms.includes.lead-timeline')
                                        @include('Lms.includes.lead-conversation')
                                        @include('Lms.includes.lead-reminders')
                                        @include('Lms.includes.whatsapp-conversation')
                                    </div>
                                    <!-- /.tab-content -->
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.nav-tabs-custom -->
                        </div>
                    </div>
                </div>
                {{-- @endif --}}
                <!-- /.card -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
        @include('Lms.modals.unsuccessfull-reason')
    </section>
    @include('Lms.js.all_js')
    @include('Lms.js.details_js')
    @include('Lms.modals.lead-reminder')
@endsection
