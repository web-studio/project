<div id="payments"></div>

<script>
    function show()
    {
        $.ajax({
            url: '/site/AjaxWindowPayments',
            cache: false,
            success: function(html){
                $("#payments").html(html);
            }
        });
    }

    $(document).ready(function(){
        show();
        setInterval('show()',30000);
    });
</script>