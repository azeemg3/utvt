<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer>
</script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js" defer></script>
<script type="text/javascript">
    var table;
$(function() {
    table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('lead.all_leads') }}",
            data: function (d) {
                d.BOXID = $(".selected").data("id");
            }
        },
        columns: [
            {
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
</script>
