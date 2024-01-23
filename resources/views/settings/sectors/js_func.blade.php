<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer></script>
   <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js" defer></script>
    <script type="text/javascript">
    var table;
    $(function () {
      table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('sector.index') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'sector', name: 'sector'},
              {data: 'city', name: 'city'},
              {data: 'country', name: 'country'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
    });

  </script>

