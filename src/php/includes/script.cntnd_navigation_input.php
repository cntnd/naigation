<script>
    /* cntnd_navigation_input */
    $( document ).ready(function() {
        $(".check-dependent").click(function(){
            if ($(this).prop('checked')){
                var dependent = $(this).data("check-dependent");
                $("#"+dependent).prop('checked', true);
            }
        });
    });
</script>