<div class="modal fade" id="add-new">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <form id="form" data-action="{{ route("sector.store") }}">
                <input type="hidden" name="id" value="0">
            <x-modal-title title="{{ __('settings.sector_details') }}" />
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('settings.country')}} <x-text-danger /></label>
                            <select id="country" class="form-control form-control-sm select2" name="country">
                                <option value="">Select Country</option>
                                {!! App\Models\Country::dropdown() !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('settings.city')}} <x-text-danger /></label>
                            <select id="cityList" class="form-control form-control-sm select2" name="city">

                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{__('settings.sector')}} <x-text-danger /></label>
                            <input type="text" class="form-control form-control-sm" placeholder="{{__('file.name')}}" name="sector">
                        </div>
                    </div>
                </div>
                <!--row-->
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
