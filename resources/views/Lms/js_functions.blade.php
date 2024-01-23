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
            url: '{{ route('lead.store') }}/',
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
                    $("#contact_name").text(data.contact_name);
                    $("#mobile").text(data.mobile);
                    $("#spo").text(data.lead_spo.name);
                } else {

                }
            }
        });
    });
</script>
