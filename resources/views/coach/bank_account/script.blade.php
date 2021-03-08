
<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            $(document).ready(function() {
                initAction();
                formSubmit();

                $('#kt_form').trigger("reset");
                $('#kt_form').attr('action',`{{url('coach/bank-account')}}/{{ Auth::guard('coach')->user()->id }}`);
                $('#kt_form').attr('method','POST');
            });

            const initAction = () => {

                $(document).on('click','.btn-clean',function(event){
                    event.preventDefault();
                    $('#kt_form').trigger("reset");
                })
            }
            formSubmit = () => {
                $('#kt_form').submit(function(event){
                    event.preventDefault();

                    btn_loading_profile_coach('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize()
                    })
                    .done(function(res, xhr, meta) {
                        if (res.status == 200) {
                            toastr.success(res.message, 'Success')
                        }
                    })
                    .fail(function(res, error) {
                        if (res.status == 422) {
                            $.each(res.responseJSON.errors, function(index, err) {
                                if (Array.isArray(err)) {
                                    $.each(err, function(index, val) {
                                        toastr.error(val, 'Failed')
                                    });
                                }
                                else {
                                    toastr.error(err, 'Failed')
                                }
                            });
                        }
                        else {
                            toastr.error(res.responseJSON.message, 'Failed')
                        }
                    })
                    .always(function() {
                        btn_loading_profile_coach('stop','Simpan Perubahan')
                    });
                });
            }

            const showModal = function (selector) {
                $('#'+selector).modal('show')
            },
            hideModal = function (selector) {
                $('#'+selector).modal('hide')
            }
        };

        return {
            init: function(){
                _componentPage();
            }
        }

    }();

    document.addEventListener('DOMContentLoaded', function() {
        Page.init();
    });

</script>

