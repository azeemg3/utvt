<div class="modal fade" id="unsuccessfull-reason-modal">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <x-modal-title title="{{ __('Lms.closed_reason') }}" />
            <form id="unsuccessfull-leadForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('Lms.select_reason') }}</label>
                                <select class="form-control form-control-sm" name="reason_id">
                                  {!! Helpers::closed_reason() !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control" name="comment"></textarea>
                        </div>
                    </div>
                    <!--row-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" onclick="lead_close_reason()" class="btn btn-primary save">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
