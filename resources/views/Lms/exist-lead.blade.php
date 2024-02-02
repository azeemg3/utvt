<div class="modal fade" id="exist-lead">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <x-modal-title title="{{ __('Lms.lead_already_exist') }}" />
            <form id="form">
                <input type="hidden" name="id" value="0">
                <div class="modal-body">
                    <div class="row">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#LeadId</th>
                                    <td id="leadId">021</td>
                                    <th>Contact Name</th>
                                    <td id="contact_name">Muhammad Azeem</td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td id="mobile">+923244659501</td>
                                    <th>SPO</th>
                                    <th id="spo">Ahsan Mahmood</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!--row-->
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="" class="btn btn-info" id="reopen-lead" style="display: none">Reopen Lead</a>
                    <a type="submit" class="btn btn-primary save" id="lead_view">Continue..</a>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
