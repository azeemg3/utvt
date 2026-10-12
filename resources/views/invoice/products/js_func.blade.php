<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
    var table;
    $(function () {
        table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('product.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'sku', name: 'sku'},
                {data: 'price', name: 'price'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#add-new').on('show.bs.modal', function (e) {
            if (!$(e.relatedTarget).attr('data-toggle')) {
                return;
            }
            $('#form').trigger('reset');
            $('#form input[name="id"]').val(0);
            $('#form textarea[name="description"]').val('');
        });
    });

    function edit_rec(thisVal) {
        var mdl = $(thisVal).attr("data-modal");
        $("#" + mdl).modal('show');
        var action = $(thisVal).attr("data-action");
        $.ajax({
            url: action,
            success: function (data) {
                $.each(data, function (key, value) {
                    $("#form input[name='" + key + "']").val(value);
                    $("#form select[name='" + key + "']").val(value);
                    $("#form textarea[name='" + key + "']").val(value);
                });
            }
        });
    }
</script>
