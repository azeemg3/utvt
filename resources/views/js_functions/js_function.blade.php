<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    //var jq = $.noConflict();
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    /**@Add new record and update new record in the system*/
    $(document).ready(function() {
        $("#form").submit(function(e) {
            e.preventDefault();
            var action = $(this).attr("data-action");
            const formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: action,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert_success('Operation Successfully..!!');
                    $("#form").trigger("reset");
                    $("#add-new").modal('hide');
                    $('.data-table').DataTable().ajax.reload();
                },
                error: function(ajaxcontent) {
                    vali = ajaxcontent.responseJSON.errors;
                    var errors = '';
                    $.each(vali, function(index, value) {
                        $("#form input[name~='" + index + "']").css('border',
                            '1px solid red');
                        errors += '&#187;' + value + '<br><br>';
                    });
                    swal.fire({
                        icon: 'error',
                        title: errors,
                    });

                }
            });
        });
    });
    /*edit funtion*/
    function edit_rec(thisVal) {
        var mdl = $(thisVal).attr("data-modal");
        $("#" + mdl).modal();
        let action = $(thisVal).attr("data-action");
        $.ajax({
            url: action,
            success: function(data) {
                for (i = 0; i < Object.keys(data).length; i++) {
                    $("#form input[name~='" + Object.keys(data)[i] + "']").val(Object.values(data)[i]);
                    $("#form select[name~='" + Object.keys(data)[i] + "']").val(Object.values(data)[i]);
                }
                $('.select2').select2();
            }
        });
    }

    function alert_success(val) {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: val,
            showConfirmButton: false,
            timer: 2000
        })
    }
    $(document).on("change", "#country", function() {
        CID = $(this).val();
        $.ajax({
            url: "{{ url('settings/city') }}/" + CID + "",
            success: function(data) {
                var htmlData = '';
                for (i in data) {
                    htmlData += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                }
                $("#cityList").html(htmlData);
            }
        });
    });
    /*delete records*/
    function del_rec(id, route) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $("#" + id).hide();
                    },
                });
                Swal.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                });
            }
        });
    }
    $(document).on('click', '.del_rec', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = $(this).data('action');
        Swal.fire({
            title: "Are you sure?",
            text: "You are going to deleting this record!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.value==true) {
                $.ajax({
                    url: url+"/"+id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Record has been deleted.",
                            icon: "success"
                        });
                        $('.data-table').DataTable().ajax.reload();
                    },
                });
            }else{

            }
        });
    });
</script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
    {{--  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>  --}}
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyBtb6OSkTsypZnJ2o1hVTJvGQw9JV__sc0",
            authDomain: "tour-vision-5a892.firebaseapp.com",
            projectId: "tour-vision-5a892",
            storageBucket: "tour-vision-5a892.appspot.com",
            messagingSenderId: "904313745421",
            appId: "1:904313745421:web:ccd18829c454a88a3e6544",
            measurementId: "G-6XDB12B751"
        };
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        <?php if(Auth::user()->device_token==''){ ?>
        $(function(){
            startFCM();
        });
        <?php } ?>
        function startFCM() {
            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken();
                })
                .then(function(response) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ url('store_token') }}',
                        type: 'POST',
                        data: {
                            token: response
                        },
                        dataType: 'JSON',
                        success: function(response) {
                                alert('Toke Saved');
                        },
                        error: function(error) {

                        },
                    });
                }).catch(function(error) {

                });
        }
        messaging.onMessage(function(payload) {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });
        $(document).ready(function(){
            $.ajax({
                url:"{{ route('fetch-notification.index') }}",
                dataType:"JSON",
                success:function(data){
                    $(".count_notify").text(data.count_notify);
                    var lead_notify='';
                    for(i in data.notify_data){
                        lead_notify+=`<div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-bullhorn mr-2"></i> `+data.notify_data[i].data.data+`
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>`;
                    }
                    $("#lead-notification").html(lead_notify);
                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-bottom-left",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "3000000",
                        "hideDuration": "10000000",
                        "timeOut": "250000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn"
                    };
                    if(data.count_notify>0)
                    toastr.success('<a href="{{ route('all-notifications')}}">You have ' + data.count_notify + ' Notifications!</a>');

                }
            });
        });
    </script>
    <!--<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>-->
