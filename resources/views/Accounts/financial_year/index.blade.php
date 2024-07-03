@extends('layout.master')
@section('mytitle', 'Business Settings')
@section('content')
    @php
        $breadcrumb[]=['title'=>'Home'];
        $breadcrumb[]=['title'=>'Accounts'];
        $breadcrumb[]=['title'=>'Finacial Year'];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card rounded-0">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <button class="btn btn-xs btn-dark float-right" onclick="add_new()">Add New</button>
                        <table class="table table-sm data-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Start Year</th>
                                <th>End Year</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="get_data"></tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <div class="pagination-panel"></div>
                    </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    @include('Accounts.financial_year.modal')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                url:"{{ route('financial_year.store') }}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"POST",
                dataType:"JSON",
                data:$("#form").serialize(),
                success:function (data) {
                    $("#form input[name~='id']").val(0);
                    toastr.success('Operation Successfully..');
                    document.getElementById("form").reset();
                    $("#new").modal('hide');
                    get_data();
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
        get_data();
        function get_data(page){
            //$("#loader").show();
            $.ajax({
                url:"{{ url('Accounts/get_financial_year') }}?page="+page,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"POST",
                dataType:"JSON",
                data:$("#search-form").serialize(),
                success:function (data) {
                    htmlData='';
                    for(i in data){
                        htmlData+='<tr id="'+data[i].id+'">';
                        htmlData+='<td>'+(Number(i)+1)+'</td>';
                        htmlData+='<td>'+data[i].start_year+'</td>';
                        htmlData+='<td>'+data[i].end_year+'</td>';
                        htmlData+='<td>'+data[i].created_at+'</td>';
                        htmlData+='<td>';
                        htmlData += '<a  class="btn btn-primary btn-xs" href="javascript:void(0)" onclick="edit(' + data[i].id + ')"><i class="fa fa-edit"></i> </a>';

                        htmlData+=' <a  class="btn btn-danger btn-xs" href="javascript:void(0)" onclick="del_rec(\''+data[i].id+'\', \'{{ url('Accounts/financial_year/') }}/'+data[i].id+'\')"><i class="fa fa-trash"></i> </a>';
                        htmlData+='</td>';
                        htmlData+='</tr>';
                    }
                    $("#get_data").html(htmlData);
                    $("#loader").hide();
                }
            })
        }
        function edit(id) {
            $("#new").modal();
            $.ajax({
                url: "{{ url('Accounts/financial_year') }}/" + id + "/edit",
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
@endsection
