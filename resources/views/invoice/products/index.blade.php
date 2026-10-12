@extends('layout.master')
@section('mytitle', 'Products')
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Invoice'];
        $breadcrumb[] = ['title' => 'Products'];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        @include('invoice.products.modal')
                        <x-add-new-btn btnId="add-new" />
                        <div class="table-responsive">
                            <table class="table table-sm data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
@include('invoice.products.js_func')
@endpush
