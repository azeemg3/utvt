@extends('layout.master')
@section('mytitle', 'Business Settings')
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Settings'];
        $breadcrumb[] = ['title' => __('settings.all_permission')];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="col-md-12">
                        @if(Session::has('message'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                            {{ Session::get('message') }}
                          </div>
                    </div>
                    @endif
                    <div class="card-header">
                        <h3>Role:{{ $role }}</h3>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <form method="POST" action="{{ route('permission.store') }}">
                                @CSRF
                                <input type="hidden" name="role_id" value="{{ $id }}">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>{{ __('settings.modules') }}</th>
                                            <th>{{ __('settings.select_all') }}</th>
                                            <th>{{ __('settings.permission') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Application Settings</td>
                                            <td>
                                                <div class="form-group clearfix">
                                                    <div class="icheck-primary d-inline select_all">
                                                        <input type="checkbox" id="application_setting_all">
                                                        <label for="application_setting_all">Select All</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-warning d-inline">
                                                        <input name="permission[]" type="checkbox"
                                                            id="application_setting_view" value="application_setting_view" @if(in_array('application_setting_view',$permissions)) checked @endif>
                                                        <label for="application_setting_view">View</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('settings.user') }}</td>
                                            <td>
                                                <div class="form-group clearfix select_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="user_select_all">
                                                        <label for="user_select_all">{{ __('settings.select_all') }}</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input name="permission[]" type="checkbox" id="user_add" value="user_add" @if(in_array('user_add',$permissions)) checked @endif>
                                                        <label for="user_add">Add</label>
                                                    </div>
                                                    <div class="icheck-primary d-inline">
                                                        <input name="permission[]" type="checkbox" id="user_edit" value="user_edit" @if(in_array('user_edit',$permissions)) checked @endif>
                                                        <label for="user_edit">Edit</label>
                                                    </div>
                                                    <div class="icheck-warning d-inline">
                                                        <input name="permission[]" type="checkbox" id="user_view" value="user_view" @if(in_array('user_view',$permissions)) checked @endif>
                                                        <label for="user_view">View</label>
                                                    </div>
                                                    <div class="icheck-danger d-inline">
                                                        <input name="permission[]" type="checkbox" id="user_delete" value="user_delete" @if(in_array('user_delete',$permissions)) checked @endif>
                                                        <label for="user_delete">Delete</label>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('settings.role') }}</td>
                                            <td>
                                                <div class="form-group clearfix">
                                                    <div class="icheck-primary d-inline select_all">
                                                        <input type="checkbox" id="role_select_all">
                                                        <label for="role_select_all">Select All</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input name="permission[]" type="checkbox" id="role_add" value="role_add" @if(in_array('role_add',$permissions)) checked @endif>
                                                        <label for="role_add">Add</label>
                                                    </div>
                                                    <div class="icheck-primary d-inline">
                                                        <input name="permission[]" type="checkbox" id="role_edit" value="role_edit" @if(in_array('role_edit',$permissions)) checked @endif>
                                                        <label for="role_edit">Edit</label>
                                                    </div>
                                                    <div class="icheck-warning d-inline">
                                                        <input name="permission[]" type="checkbox" id="role_view" value="role_view" @if(in_array('role_view',$permissions)) checked @endif>
                                                        <label for="role_view">View</label>
                                                    </div>
                                                    <div class="icheck-danger d-inline">
                                                        <input name="permission[]" type="checkbox" id="role_delete" value="role_delete" @if(in_array('role_delete',$permissions)) checked @endif>
                                                        <label for="role_delete">Delete</label>
                                                    </div>
                                                    <div class="icheck-success d-inline">
                                                        <input name="permission[]" type="checkbox"
                                                            id="role_assign_permission" value="role_assign_permission" @if(in_array('role_assign_permission',$permissions)) checked @endif>
                                                        <label for="role_assign_permission">Assign Permission</label>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>LMS</td>
                                            <td>
                                                <div class="form-group clearfix">
                                                    <div class="icheck-primary d-inline select_all">
                                                        <input type="checkbox"
                                                            id="lms_select_all">
                                                        <label for="lms_select_all">Select All</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input name="permission[]" type="checkbox" id="lms_dashboard" value="lms_dashboard" @if(in_array('role_assign_permission',$permissions)) checked @endif>
                                                        <label for="lms_dashboard">{{ __('lms.lms_dashboard') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input name="permission[]" type="checkbox" id="lms_my_leads" value="lms_my_leads" @if(in_array('role_assign_permission',$permissions)) checked @endif>
                                                        <label for="lms_my_leads">{{ __('lms.my_leads') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input name="permission[]" type="checkbox" id="lms_all_leads" value="lms_all_leads" @if(in_array('lms_all_leads',$permissions)) checked @endif>
                                                        <label for="lms_all_leads">{{ __('lms.all_leads') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input name="permission[]" type="checkbox" id="lead_add" value="lead_add" @if(in_array('lead_add',$permissions)) checked @endif>
                                                        <label for="lead_add">Add</label>
                                                    </div>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="permission[]" id="lead_edit" value="lead_edit" @if(in_array('lead_edit',$permissions)) checked @endif>
                                                        <label for="lead_edit">Edit</label>
                                                    </div>
                                                    <div class="icheck-warning d-inline">
                                                        <input type="checkbox" name="permission[]" id="lead_view" value="lead_view" @if(in_array('lead_view',$permissions)) checked @endif>
                                                        <label for="lead_view">View</label>
                                                    </div>
                                                    <div class="icheck-danger d-inline">
                                                        <input type="checkbox" name="permission[]" id="lead_delete" value="lead_delete" @if(in_array('lead_delete',$permissions)) checked @endif>
                                                        <label for="lead_delete">Delete</label>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Business Setup</td>
                                            <td>
                                                <div class="form-group clearfix select_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="business_setup_all">
                                                        <label for="business_setup_all">Select All</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="permission[]" id="bs_add" value="bs_add" @if(in_array('bs_add',$permissions)) checked @endif>
                                                        <label for="bs_add">Add</label>
                                                    </div>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="permission[]" id="bs_edit" value="bs_edit" @if(in_array('bs_edit',$permissions)) checked @endif>
                                                        <label for="bs_edit">Edit</label>
                                                    </div>
                                                    <div class="icheck-warning d-inline">
                                                        <input type="checkbox" name="permission[]" id="bs_view" value="bs_view" @if(in_array('bs_view',$permissions)) checked @endif>
                                                        <label for="bs_view">View</label>
                                                    </div>
                                                    <div class="icheck-danger d-inline">
                                                        <input type="checkbox" name="permission[]" id="bs_delete" value="bs_delete" @if(in_array('bs_delete',$permissions)) checked @endif>
                                                        <label for="bs_delete">Delete</label>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('settings.country') }}</td>
                                            <td>
                                                <div class="form-group clearfix select_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="country_select_all">
                                                        <label
                                                            for="country_select_all">{{ __('settings.select_all') }}</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="permission[]" id="country_add" value="country_add" @if(in_array('country_add',$permissions)) checked @endif>
                                                        <label for="country_add">Add</label>
                                                    </div>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="permission[]" id="country_edit" value="country_edit" @if(in_array('country_edit',$permissions)) checked @endif>
                                                        <label for="country_edit">Edit</label>
                                                    </div>
                                                    <div class="icheck-warning d-inline">
                                                        <input type="checkbox" name="permission[]" id="country_view" value="country_view" @if(in_array('country_view',$permissions)) checked @endif>
                                                        <label for="country_view">View</label>
                                                    </div>
                                                    <div class="icheck-danger d-inline">
                                                        <input type="checkbox" name="permission[]"
                                                            id="country_delete" value="country_delete" @if(in_array('country_delete',$permissions)) checked @endif>
                                                        <label for="country_delete">Delete</label>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>City</td>
                                            <td>
                                                <div class="form-group clearfix select_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="city_select_all">
                                                        <label
                                                            for="city_select_all">{{ __('settings.select_all') }}</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="permission[]" id="city_add" value="city_add" @if(in_array('city_add',$permissions)) checked @endif>
                                                        <label for="city_add">Add</label>
                                                    </div>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="permission[]" id="city_edit" value="city_edit" @if(in_array('city_edit',$permissions)) checked @endif>
                                                        <label for="city_edit">Edit</label>
                                                    </div>
                                                    <div class="icheck-warning d-inline">
                                                        <input type="checkbox" name="permission[]" id="city_view" value="city_view" @if(in_array('city_view',$permissions)) checked @endif>
                                                        <label for="city_view">View</label>
                                                    </div>
                                                    <div class="icheck-danger d-inline">
                                                        <input type="checkbox" name="permission[]" id="city_delete" value="city_delete" @if(in_array('city_delete',$permissions)) checked @endif>
                                                        <label for="city_delete">Delete</label>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('settings.source_of_query') }}</td>
                                            <td>
                                                <div class="form-group clearfix select_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="source_query_select_all" value="">
                                                        <label
                                                            for="source_query_select_all">{{ __('settings.select_all') }}</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="permission[]"
                                                            id="source_query_add" value="source_query_add" @if(in_array('source_query_add',$permissions)) checked @endif>
                                                        <label for="source_query_add">Add</label>
                                                    </div>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="permission[]"
                                                            id="source_query_edit" value="source_query_edit" @if(in_array('source_query_edit',$permissions)) checked @endif>
                                                        <label for="source_query_edit">Edit</label>
                                                    </div>
                                                    <div class="icheck-warning d-inline">
                                                        <input type="checkbox" name="permission[]"
                                                            id="source_query_view" value="source_query_view" @if(in_array('source_query_view',$permissions)) checked @endif>
                                                        <label for="source_query_view">View</label>
                                                    </div>
                                                    <div class="icheck-danger d-inline">
                                                        <input type="checkbox" name="permission[]"
                                                            id="source_query_delete" value="source_query_delete" @if(in_array('source_query_delete',$permissions)) checked @endif>
                                                        <label for="source_query_delete">Delete</label>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('settings.services') }}</td>
                                            <td>
                                                <div class="form-group clearfix select_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="service_select_all">
                                                        <label
                                                            for="service_select_all">{{ __('settings.select_all') }}</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group clearfix selected_all">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="permission[]" id="service_add" value="service_add" @if(in_array('service_add',$permissions)) checked @endif>
                                                        <label for="service_add">Add</label>
                                                    </div>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="permission[]" id="service_edit" value="service_edit" @if(in_array('service_edit',$permissions)) checked @endif>
                                                        <label for="service_edit">Edit</label>
                                                    </div>
                                                    <div class="icheck-warning d-inline">
                                                        <input type="checkbox" name="permission[]" id="service_view" value="service_view" @if(in_array('service_view',$permissions)) checked @endif>
                                                        <label for="service_view">View</label>
                                                    </div>
                                                    <div class="icheck-danger d-inline">
                                                        <input type="checkbox" name="permission[]"
                                                            id="service_delete" value="service_delete" @if(in_array('service_delete',$permissions)) checked @endif>
                                                        <label for="service_delete">Delete</label>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-success float-right"><i class="fa fa-save"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
    @include('settings.permission.js_func')
@endsection
