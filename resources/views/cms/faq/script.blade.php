<script>
    $('.btn').click(function () {
        $(this).toggleClass("rotate-icon");
    });
</script>


<script type="text/javascript">
	$('#form-question').submit(function(event){
        event.preventDefault();
        // alert('asdasdasd')
        // btn_loading('start')
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize()
        })
        .done(function(res, xhr, meta) {
            console.log(res)
        })
        .fail(function(res, error) {
            toastr.error(res.responseJSON.message, 'Failed')
        })
        .always(function() {
            // btn_loading('stop')
        });
    });
</script>