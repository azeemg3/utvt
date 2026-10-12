@extends('layout.master')
@section('mytitle', 'Invoices')
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Invoice'];
        $breadcrumb[] = ['title' => 'All Invoices'];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <a href="{{ route('invoice.create') }}" class="btn btn-sm btn-info float-right">
                        <i class="fas fa-plus"></i> Create Invoice
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
@include('invoice.invoices.js_func')
@endpush
