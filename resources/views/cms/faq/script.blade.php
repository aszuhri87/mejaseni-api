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
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-tertiary',
                    cancelButton: 'btn btn-tertiary ml-3'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Berhasil!',
                padding: '2em',
                icon: 'success',
                text: "Pertanyaan anda sudah terkirim."
            })
        })
        .fail(function(res, error) {
            toastr.error(res.responseJSON.message, 'Failed')
        })
        .always(function() {
        });
    });
</script>