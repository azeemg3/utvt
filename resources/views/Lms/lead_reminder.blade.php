@extends('layout.master')
@section('mytitle', 'Lead Reminders')
@section('content')
    @php
        $breadcrumb[]=['title'=>'Home'];
        $breadcrumb[]=['title'=>'LMS'];
        $breadcrumb[]=['title'=>__('lms.lead_reminder')];
    @endphp
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        @include('settings.airline.modal')
                        <x-add-new-btn btnId="add-new" />
                        <div class="table-responsive">
                            <table class="table table-sm data-table">
                                <thead>
                                <tr>
                                    <th>#leadId</th>
                                    <th>Contact Name</th>
                                    <th>Message</th>
                                    <th>Reminder Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
        @include('Lms.modals.lead-reminder')
    </section>
@endsection
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer></script>
   <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js" defer></script>
    <script type="text/javascript">
    var table;
    $(function () {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{{ route('lead.lead_reminder') }}",
            data:{type:{{$type??"0"}}
        },
        },
        "dataSrc": "",
        columns: [
            {data: 'leadId', name: 'leadId'},
            {data: 'lead.contact_name', name: 'lead.contact_name'},
            {data: 'message',
             render: function(data, type, row) {
                 return $('<div/>').html(data).text();
             }
            },
            {data: 'reminder_date', name: 'reminder_date'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
$(document).on("click",".reminder-read",function(){
        id=$(this).data("id");
        Swal.fire({
            title: "Are you sure?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Mark as read!"
        }).then((result) => {
            console.log(result.value);
            if (result.value==true) {
                $.ajax({
                    url:"{{route('lead.reminder_read')}}",
                    type: 'GET',
                    data:{id:id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('.data-table').DataTable().ajax.reload();
                    },
                });
            }
        });
    });
    $(document).on("click",".lead-reminder-update",function () {
        $("#lead-reminder-modal").modal("show");
        id=$(this).data("id");
        $.ajax({
                    url:"{{route('lead.edit_reminder')}}",
                    type: 'GET',
                    data:{id:id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $("#lead-reminder-modal input[name~='reminder_date']").val(data.reminder_date);
                        $("#lead-reminder-modal input[name~='reminder_time']").val(data.reminder_time);
                    },
                });
    });

  </script>

