<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script>
    $(document).on("click", ".create_lead", function() {
        $("#loader").show();
        let type = $(this).data("spo");
        var formData = $("#lead-form").serializeArray();
        formData.push({
            name: 'type',
            value: type
        });
        $.ajax({
            type: 'POST',
            url: '{{ route('lead.store') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function(data) {
                alert_success('Operation Successfully..!!');
                $("#lead-form").trigger("reset");
                $("#loader").hide();
                $(".select2").val('').trigger('change');
            },
            complete: function (data) {
                queue_jobs();
               },
            error: function(ajaxcontent) {
                vali = ajaxcontent.responseJSON.errors;
                var errors = '';
                $.each(vali, function(index, value) {
                    $("#lead-form input[name~='" + index + "']").css('border',
                        '1px solid red');
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
                    toastr.error(value)
                });
                $("#loader").hide();
            }
        });
    });
    //update lead
    $(document).on("click", ".update_lead", function() {
        $("#loader").show();
        var formData = $("#lead-form").serializeArray();
        id=$("#leadId").val();
        $.ajax({
            type: 'PUT',
            url: '{{ url("lms/lead") }}/'+id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function(data) {
                alert_success('Operation Successfully..!!');
                $("#loader").hide();
            },
            error: function(ajaxcontent) {
                vali = ajaxcontent.responseJSON.errors;
                var errors = '';
                $.each(vali, function(index, value) {
                    $("#lead-form input[name~='" + index + "']").css('border',
                        '1px solid red');
                    toastr.error(value)
                });
                $("#loader").hide();
            }
        });
    });
    $(document).on("click", ".reopen_lead", function() {
        $("#loader").show();
        var formData = $("#reopen-lead-form").serializeArray();
        id=$("#leadId").val();
        $.ajax({
            type: 'PUT',
            url: '{{ url("lms/lead_reopen") }}/',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function(data) {
                alert_success('Operation Successfully..!!');
                $("#loader").hide();
            },
            error: function(ajaxcontent) {
                vali = ajaxcontent.responseJSON.errors;
                var errors = '';
                $.each(vali, function(index, value) {
                    $("#lead-form input[name~='" + index + "']").css('border',
                        '1px solid red');
                    toastr.error(value)
                });
                $("#loader").hide();
            }
        });
    });
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
        nationalMode: false,
        initialCountry: "PK",
    });
    $(document).on("change", "#phone", function() {
        var thisVal = $(this).val();
        $.ajax({
            url: "{{ url('lms/check-lead') }}/" + thisVal,
            type: "GET",
            success: function(data) {
                if (data != '') {
                    $("#exist-lead").find(".close").remove();
                    $("#exist-lead").modal({backdrop: 'static',
                    keyboard: false});
                    $("#leadId").text(data.id);
                    $("#form input[name~='id']").val(data.id);
                    if(data.BOXID==18 || data.BOXID==19){
                        $("#reopen-lead").show().attr("href","{{ url('lms/reopen/') }}/"+data.id);
                    }
                    $("#lead_view").attr("href","{{ url('lms/lead') }}/"+data.id);
                    $("#contact_name").text(data.contact_name);
                    $("#mobile").text(data.mobile);
                    $("#spo").text(data.lead_spo.name);
                } else {

                }
            }
        });
    });
    function queue_jobs(){
        $.ajax({
            url:"{{ url('queue_run') }}",
            success:function(data){

            }
        })
    }
</script>
