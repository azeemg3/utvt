@extends('layout.master')
@section('mytitle', 'Business Settings')
@section('content')
    @php
        $breadcrumb[]=['title'=>'Home'];
        $breadcrumb[]=['title'=>'All Notifications'];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-sm data-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Notification</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notify_data as $item)
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $item->data['data']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
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
@include('notifications.js_func')
