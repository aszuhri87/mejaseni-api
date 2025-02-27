<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {

            $(document).ready(function () {
                $(document).on('change','.r-input',function(){
                    if($(this).val() == 'va'){
                        $('.list-bank-va').show()
                    }else{
                        $('.list-bank-va').hide()
                    }
                })

                $(document).on('click','#btn-next',function(event){
                    event.preventDefault();

                    if($("input[name=payment_method]:checked").val()){
                        $('#btn-next').html('<div id="loading" class="mr-1"></div> Loading...');
                        $('#btn-next').attr('disabled', true);
                        $.ajax({
                            url: "{{url('cart-payment')}}",
                            type: 'POST',
                            data: {
                                type: $("input[name=payment_method]:checked").val(),
                                va_chanel:  $("input[name=payment_chanel]:checked").val()
                            }
                        }).done(function(res, xhr, meta){
                            window.location = res.redirect_url;
                        }).fail(function(res, error) {
                            alert('Proses gagal, silakan coba kembali.')
                        })
                        .always(function() {
                            $('#btn-next').html(`Lanjutkan Pembayaran <img class="ml-2" src="assets/img/svg/Arrow-right.svg" alt="">`);
                            $('#btn-next').attr('disabled', false);
                        });
                    }else{
                        alert('Silakan pilih metode pembayaran!')
                        return false;
                    }

                })

                $(document).on('click','#btn-next-zero',function(event){
                    event.preventDefault();

                    $.ajax({
                        url: "{{url('cart-payment-zero')}}",
                        type: 'POST',
                    }).done(function(res, xhr, meta){
                        window.location = res.redirect_url;
                    })
                })
            });
        };

        return {
            init: function () {
                _componentPage();
            }
        }
    }();
    document.addEventListener('DOMContentLoaded', function () {
        Page.init();
    });

</script>
