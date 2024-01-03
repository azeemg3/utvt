<div class="modal fade" id="lead-details-modal">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <x-modal-title title="{{ __('Lms.lead_details') }}" />
            <form action="{{ route('lead.takeover') }}" method="POST">
                <input type="hidden" id="id" name="id" value="0">
                @CSRF
                <div class="modal-body">
                    <div class="row">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#LeadId</th>
                                    <th>Contact Name</th>
                                    <th>Mobile</th>
                                </tr>
                                <tr>
                                    <td id="leadId"></td>
                                    <td id="contact_name"></td>
                                    <td id="mobile"></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!--row-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary save">Takeover Lead..</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
