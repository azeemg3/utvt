@extends('layout.master')
@section('mytitle', 'Business Settings')
@section('content')
    @php
        $breadcrumb[]=['title'=>'Home'];
        $breadcrumb[]=['title'=>'Settings'];
        $breadcrumb[]=['title'=>__('settings.all_roles')];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        @include('settings.roles.modal')
                        <x-add-new-btn btnId="add-new" />
                        <div class="table-responsive">
                            <table class="table table-sm data-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('settings.role_name') }}</th>
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
@endsection
@include('settings.roles.js_func');
