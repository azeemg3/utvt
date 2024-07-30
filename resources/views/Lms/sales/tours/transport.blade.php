<div class="tab-pane fade" id="tour-transport" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
    <form id="tour-transport-form">
        <input type="hidden" name="id" value="0">
        <input type="hidden" name="account_code" value="{{ $data->ledger }}">
        <div class="row">
            <div class="form-group col-md-12">
                <select name="pax_name[]" class="form-control form-control-sm select2 tour_pax_list" multiple data-placeholder="Select Passengers">
                </select>
            </div>
            <!--col-->
            <div class="form-group col-md-2">
                <label for="exampleInputEmail1">Vehicle Type</label>
                <select name="vehicle_type" class="form-control form-control-sm select2">
                    {{-- {!! Helpers::vehicle_types() !!} --}}
                </select>
            </div>
            <!--col-->
            <div class="form-group col-md-2">
                <label>From Date</label>
                <input type="text" name="from_date" class="form-control form-control-sm date" placeholder="From Date">
            </div>
            <!--col-->
            <div class="form-group col-md-2">
                <label>To Date</label>
                <input type="text" name="to_date" class="form-control form-control-sm date" placeholder="To Date">
            </div>
            <!--col-->
            <div class="form-group col-md-2">
                <label>Rate</label>
                <input type="text" name="receiveable" class="form-control form-control-sm" placeholder="Rate">
            </div>
            <!--col-->
            <div class="form-group col-md-2">
                <label for="exampleInputEmail1">Currency</label>
                <select name="currency" class="form-control form-control-sm">
                    <option value="">Pkr</option>
                </select>
            </div>
            <!--col-->
            <div class="form-group col-md-2">
                <label for="exampleInputEmail1">Currency Rate</label>
                <input type="text" name="currency_rate" class="form-control form-control-sm" placeholder="Currency Rate">
            </div>
            <!--col-->
        </div>
        <!-- Modal footer -->
        <div class="clearfix"></div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success btn-xs" onclick="save_tour_rec('{{ url('lms/tour_transport_store') }}', 'tour-transport-form', 'transport')">Submit</button>
            <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal" onclick="close_form(5)">Close</button>
        </div>
        <div class="modal-footer">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr class="table-active">
                        <th>#</th>
                        <th>passport</th>
                        <th>Pax Name</th>
                        <th>Pax Type</th>
                        <th>Vehicle Type</th>
                        <th>Receiveable</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody class="get_transport_invDetails"></tbody>
                </table>
            </div>
        </div>
    </form>
</div>
