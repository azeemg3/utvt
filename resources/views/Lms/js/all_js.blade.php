<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer>
</script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js" defer></script>
<script type="text/javascript">
    var table;
$(function() {
    $('body').append('<div id="custom-loading" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">Loading...</div>');
    table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('lead.all_leads') }}",
            data: function (d) {
                d.BOXID = $(".selected").data("id");
                d.leadId=$("#lead_number").val();
                d.mobile=$("#lead_mobile").val();
            }
        },
        columns: [
            {
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: ''
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
                render: function(data, type, row, meta) {
                    // Ensure the data is returned as HTML
                    return type === 'display' && data ? data : data;
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],
        createdRow: function (row, data, dataIndex) {
            customRowCallback(row, data, dataIndex);
        }
    });
    $("#search_lead").click(function(){
        table.ajax.reload();
    });
});


// Custom row callback function
function customRowCallback(row, data, dataIndex) {
    $(row).addClass('custom-class' + dataIndex);

    // Create the child row
    var childRow = $('<tr class="child-row"><td colspan="8">This is a child row for ' + data.leadId + '</td></tr>');

    // Append the child row to the parent table
    $('.data-table').append(childRow);
}

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
    $(document).on("click", ".lead-remarks", function() {
        $("#lead-remarks-modal").modal();
        var remarks=$(this).data("conversation");
        $("#lead-remarks-conversation").html(remarks);
    });
</script>
