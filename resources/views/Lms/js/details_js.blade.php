<script>
    /*Client conversation*/
    function lead_conversation() {
        $("#loader").show();
        $.ajax({
            url: "{{ route('lead.conversation') }}",
            type: "POST",
            data: $("#lead-conversation-form").serialize(),
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                document.getElementById("lead-conversation-form").reset();
                $("#conversation-by").text(data.user.name);
                $("#conversation-time").text(data.created_at);
                $("#conversation-message").html(data.conversation);
                $("#loader").hide();
                $('.textarea').summernote('code', '');
            },error: function(ajaxcontent) {
                    vali = ajaxcontent.responseJSON.errors;
                    var errors = '';
                    $.each(vali, function(index, value) {
                        $("#lead-conversation-form textarea[name~='" + index + "']").css('border',
                            '1px solid red');
                        errors += '&#187;' + value + '<br><br>';
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                    };
                        toastr.error(value);

                    });
                    $("#loader").hide();
                }
        });
    }
    /*Change manual status*/
    $(document).on("change", "#manual-status", function(e) {
        e.preventDefault();
        thisVal = $(this).val();
        Swal.fire({
            title: "Are you sure?",
            text: "You want to change Status..??",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Change it!"
        }).then((result) => {
            if (result.value == true) {
                if(thisVal==5){
                    $("#unsuccessfull-reason-modal").modal();
                    return false;
                }
                $.ajax({
                    url: "{{ url('lms/change-status') }}/" + "{{ $data->id }}/" + thisVal,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        if (data == 1) {
                            Swal.fire({
                                title: "Status Updated",
                                text: "Lead Status Updated Successfully..!!",
                                icon: "success"
                            });
                            setTimeout(function() {
                                location.reload(true);
                            }, 2000);
                        }
                    }
                });
            } else {

            }
        });
    });
    //close lead with reason
    function lead_close_reason(){
        $.ajax({
            url:'{{ route('lead.lead_reason') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            data:$("#unsuccessfull-leadForm").serialize()+'&leadId={{ $data->id }}',
            success:function(data){
                if (data == 1) {
                    Swal.fire({
                        title: "Status Updated",
                        text: "Lead Status Updated Successfully..!!",
                        icon: "success"
                    });
                    setTimeout(function() {
                        location.reload(true);
                    }, 2000);
                }
            }
        });
    }
    $(function() {
        // Summernote
        $('.textarea').summernote();
    });
    //get lead conversion
    function get_lead_conversation(id) {
        $.ajax({
            url: "{{ url('lms/lead-conversation') }}/" + id,
            type: "GET",
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                var htmlData = '';
                for (i in data) {
                    htmlData += `<!-- timeline time label -->
                <div class="time-label">
                    <span class="bg-gray">`+data[i].created_at+`</span>
                </div>
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <div>
                    <i class="`+conversation_via(data[i].contact_via)+`"></i>
                    <div class="timeline-item">
                        <h3 class="timeline-header"><a href="#">`+data[i].user.name+`</a></h3>
                        <div class="timeline-body">`+data[i].conversation+`</div>
                    </div>
                </div>
                <!-- END timeline item -->`;
                }
                htmlData += `<!-- END timeline item -->
                <div>
                    <i class="fas fa-clock bg-gray"></i>
                </div>`;
                $("#client-lead-conversation").html(htmlData);
            }
        });
    }
     //conversation via
     function conversation_via(type){
        if(type=='phone'){
            return 'fas fas fa-phone bg-info';
        }else if(type=='whatsapp'){
            return 'fab fa-whatsapp bg-success';
        }else if(type=='email'){
            return 'fas fa-envelope bg-primary';
        }
    }
    $(document).on("change","#reminder-button",function(){
        if($(this).is(':checked')) {
            $(".reminder").show();
          } else {
            $(".reminder").hide();
          }
    });
    $(document).on("change","#lead-transfer",function(){
        var spo=$(this).val();
        var leadId=$(this).data("leadid");
        Swal.fire({
            title: "Are you sure?",
            text: "You are going to Transfering lead",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Transfer it!"
        }).then((result) => {
            if (result) {
                $.ajax({
                    url:"{{ route('lead.lead_transfer') }}",
                    data:{leadId:leadId,spo:spo},
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {

                    },
                });
                Swal.fire({
                    title: "Transfered!",
                    text: "Lead has been transfered.",
                    icon: "success"
                });
            }
        });
    });
</script>
