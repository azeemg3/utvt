<script>
     var table;
    function add_new() {
        $("#new").modal();
        $(".select2").select2();
        document.getElementById("form").reset();
        $("#form input[name~='id']").val(0);
        $("#new").find('.btn-success').text('Submit');
    }
    tostr_options('toast-top-right');
    function save_rec() {
        $("#loader").show();
        $.ajax({
            url:"{{ route('payment_vouchers.store') }}",
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
                table.ajax.reload();
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
            url: "{{ url('Accounts/vouchers/payment_vouchers') }}/" + id + "/edit",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                for (i=0; i<Object.keys(data).length; i++){
                    $("#form input[name~='"+Object.keys(data)[i]+"']").val(Object.values(data)[i]);
                    $("#form select[name~='"+Object.keys(data)[i]+"']").val(Object.values(data)[i]);
                    $("#form textarea[name~='narration'").val(Object.values(data)[i]);
                }
                $('.select2').select2();
            }
        })
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer></script>
   <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js" defer></script>
    <script type="text/javascript">

    $(function () {
      table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('payment_vouchers.index') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'trans_code', name: 'trans_code'},
              {data: 'trans_acc.Trans_Acc_Name', name: 'trans_acc.Trans_Acc_Name'},
              {data: 'trans_date', name: 'trans_date'},
              {data: 'remarks', name: 'remarks'},
              {data: 'amount', name: 'amount'},
              {data: 'amount', name: 'amount'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
    });

  </script>

