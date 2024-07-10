@extends('layout.master')
@section('mytitle', 'Business Settings')
@section('content')
    @php
        $breadcrumb[]=['title'=>'Home'];
        $breadcrumb[]=['title'=>'Accounts'];
        $breadcrumb[]=['title'=>'Transaction A/C List'];
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
                                    <th>Code</th>
                                    <th>Trnsaction A/C Name</th>
                                    <th>subhead A/C Name</th>
                                    <th>Current Balance</th>
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
    @include('Accounts.trans_accounts.modal')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @include('Accounts.trans_accounts.js_func')
@endsection
