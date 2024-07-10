@extends('layout.master')
@section('mytitle', 'Business Settings')
@section('content')
    @php
        $breadcrumb[]=['title'=>'Home'];
        $breadcrumb[]=['title'=>'Accounts'];
        $breadcrumb[]=['title'=>'Subhead Accounts'];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card rounded-0">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <button class="btn btn-xs btn-dark float-right" onclick="add_new()">Add New</button>
                        <table class="table table-sm data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subhead A/C Name</th>
                                    <th>Head A/C Name</th>
                                    <th>Root A/C Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    <!-- /.content -->
    @include('Accounts.subhead_accounts.modal')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @include('Accounts.subhead_accounts.js_func')
@endsection
