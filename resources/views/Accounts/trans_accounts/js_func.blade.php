<script>
    function add_new() {
        $("#new").modal();
        $(".select2").select2();
        document.getElementById("form").reset();
        $("#form input[name~='id']").val(0);
        $("#new").find('.btn-success').text('Submit');
    }
    function save_rec() {
        $("#loader").show();
        $.ajax({
            url:"{{ route('subhead_accounts.store') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:"POST",
            dataType:"JSON",
            data:$("#form").serialize(),
            success:function (data) {
                $("#form input[name~='id']").val(0);
                toastr.success('Operation Successfully..');
                document.getElementById("form").reset();
                $("#new").modal('hide');
                $("#loader").hide();
            },error:function(ajaxcontent) {
                vali=ajaxcontent.responseJSON.errors;
                var errors='';
                $.each(vali, function( index, value ) {
                    $("#form input[name~='" + index + "']").css('border', '1px solid red');
                    toastr.error(value);
                });
                $("#loader").hide();
            }
        })
    }
    function edit(id) {
        $("#new").modal();
        $.ajax({
            url: "{{ url('Accounts/subhead_accounts') }}/" + id + "/edit",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                for (i=0; i<Object.keys(data).length; i++){
                    $("#form input[name~='"+Object.keys(data)[i]+"']").val(Object.values(data)[i]);
                    $("#form select[name~='"+Object.keys(data)[i]+"']").val(Object.values(data)[i]);
                }
                $('.select2').select2();
                $("#new").find(".btn-success").text('Update');
            }
        })
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer></script>
   <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js" defer></script>
    <script type="text/javascript">
    var table;
    $(function () {
      table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('trans_accounts.index') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'code', name: 'code'},
              {data: 'Trans_Acc_Name', name: 'Trans_Acc_Name'},
              {data: 'subhead.name', name: 'subhead.name'},
              {data: 'OB', name: 'OB'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
    });

  </script>
