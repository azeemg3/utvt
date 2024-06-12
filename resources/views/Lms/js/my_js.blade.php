<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer>
</script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js" defer></script>
<script type="text/javascript">
    var table;
    $(function() {
        var i = 0;
        table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
            url: "{{ route('lead.my_leads') }}",
            data: function (d) {
                d.BOXID = $(".selected").data("id");
                d.leadId=$("#lead_number").val();
                d.mobile=$("#lead_mobile").val();
            }
        },
            columns: [{
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'leadId',
                    name: 'leadId'
                },
                {
                    data: 'contact_name',
                    name: 'contact_name'
                },
                {
                    data: 'mobile',
                    name: 'mobile'
                },
                {
                    data: 'spo_name',
                    name: 'spo_name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'lead_status',
                    name: 'lead_status',
                },
                {
                    data: 'remarks',
                    name: 'remarks',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        $("#search_lead").click(function(){
            $(".lead_action").removeClass("selected");
            table.ajax.reload();
        });
    });
    $(".lead_action").on("click",function(){
            $(".lead_action").removeClass("selected");
            $(this).addClass("selected");
            table.ajax.reload();
        });
     /*Lead Takeover*/
     $(document).on("click", "#lead-takeover", function() {
        $("#lead-details-modal").modal();
        var thisVal = $(this).data('id');
        $.ajax({
            url: "{{ url('lms/check-lead') }}/" + thisVal,
            type: "GET",
            success: function(data) {
                if (data != '') {
                    $("#id").val(data.id);
                    $("#leadId").text(data.id);
                    $("#contact_name").text(data.contact_name);
                    $("#mobile").text(data.mobile);
                } else {

                }
            }
        });
    });
</script>
