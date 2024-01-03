@extends('layout.master')
@section('mytitle', 'Business Settings')
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Settings'];
        $breadcrumb[] = ['title' => __('settings.create_user')];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                @if ($errors->any())
                    <div class="card-header">
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @if (Session::has('message'))
                <div class="card-header">
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                </div>
                @endif
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route('user.save') }}" method="post">
                            @CSRF
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input placeholder="Name" class="form-control form-control-sm" name="name"
                                            type="text" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input placeholder="Email" class="form-control form-control-sm" name="email"
                                            type="text" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Password:</label>
                                        <input placeholder="Password" required class="form-control form-control-sm"
                                            name="password" type="password">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>Confirm Password:</label>
                                        <input placeholder="Confirm Password" class="form-control form-control-sm"
                                            name="password_confirmation" type="password" value="">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <strong>Role:</strong>
                                        <select class="form-control form-control-sm select2" name="role_id">
                                            <option value="">Select Role</option>
                                            {!! Helpers::helper_dropdown() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <strong>Status:</strong>
                                        <select class="form-control form-control-sm" name="status">
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--end-row-->
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <button type="submit"
                                        class="btn btn-sm btn-success btn-flat float-right">Submit</button>
                                </div>
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
@endsection
