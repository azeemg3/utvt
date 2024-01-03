<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript">
$(document).on("click",".select_all",function(){
    console.log($(this).closest("tr").find(".selected_all").children(".d-inline").children("input:checkbox").prop('checked', true));
});
</script>
