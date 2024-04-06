<div class="modal fade" id="lead-reminder-modal">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <x-modal-title title="Reminder Update" />
            <form action="{{ route('lead.takeover') }}" method="POST">
                <input type="hidden" id="id" name="id" value="0">
                @CSRF
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Reminder Date</label>
                                <input type="text" class="form-control form-control-sm date" name="reminder_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Reminder Time</label>
                                <input type="time" class="form-control form-control-sm" name="reminder_time">
                            </div>
                        </div>
                    </div>
                    <!--row-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save">Update</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
