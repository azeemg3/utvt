<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('invoice.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'invoice_date', name: 'invoice_date'},
                {data: 'total', name: 'total'},
                {data: 'status_badge', name: 'status', orderable: false, searchable: false},
                {data: 'created_by_name', name: 'created_by_name', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
