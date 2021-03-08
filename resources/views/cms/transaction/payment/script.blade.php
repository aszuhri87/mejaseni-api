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

                    $.ajax({
                        url: "{{url('cart-payment')}}",
                        type: 'POST',
                        data: {
                            type: $("input[name=payment_method]:checked").val(),
                            va_chanel:  $("input[name=payment_chanel]:checked").val()
                        }
                    }).done(function(res, xhr, meta){
                        window.location = res.redirect_url;
                    })
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
