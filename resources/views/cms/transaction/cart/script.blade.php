<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {

            $(document).ready(function () {
                AOS.init();
                initAction();
                initCart()
            });

            const initCart = () => {
                $.ajax({
                    url: "{{url('student-cart')}}",
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = '', total = 0;

                    $.each(res.data, function(index, data){
                        element += `<div class="cart-item pb-1 mb-4">
                            <div class="row">
                                <div class="col-1">
                                    <div class="form-check">
                                        <input class="form-check-input mt-1" type="checkbox" name="data[]" value="${data.id}" id="${data.id}" checked>
                                    </div>
                                </div>
                                <div class="col-10 col-lg-8 col-xl-7 py-0 my-md-0 mr-0 pl-lg-4 pl-xl-1">
                                    <h5>${data.name ? data.name:''}</h5>
                                    <div class="badge-class mt-2 text-center">${data.type ? data.type:''}</div>
                                </div>
                                <div
                                    class="col-11 col-md-12 col-xl-4 text-left text-xl-right mt-3 mt-md-4 mt-xl-0 mt-md-0 mx-4 mx-md-0 px-4 pl-3 pl-xl-0">
                                    <div class="row row-center-spacebetween">
                                        <div class="col-9 col-md-10">
                                            <h5 style="overflow-wrap: break-word;">Rp. ${data.price ? data.price:0}</h5>
                                        </div>
                                        <div class="col-3 col-md-2">
                                            <div class="delete-item" data-id="${data.id}">
                                                <a href="">
                                                    <img src="assets/img/svg/Trash.svg" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-line mx-4 mb-4"></div>`;

                        if(data.price)
                            total += parseInt(data.price)
                    })

                    // if(total > 0){
                        $('.cart-place').show();
                        $('.empty-place').hide();
                    // }else{
                    //     $('.empty-place').show();
                    //     $('.cart-place').hide();
                    // }

                    total = total ? total:0;
                    $('#list-place').html(element);
                    $('.grand-total').html('Rp. '+ total);
                });
            },
            initAction = () => {
                $(document).on('click',".delete-item", function (e) {
                    event.preventDefault();

                    let id = $(this).attr('data-id');

                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-tertiary',
                            cancelButton: 'btn btn-tertiary ml-3'
                        },
                        buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                        title: 'Apakah Anda yakin?',
                        padding: '2em',
                        text: "Anda akan menghapus item pada keranjang Anda",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{url('cart')}}/"+id,
                                type: 'DELETE',
                                dataType: 'json',
                            }).done(function(res, xhr, meta){
                                swalWithBootstrapButtons.fire({
                                    title: 'Terhapus!',
                                    padding: '2em',
                                    icon: 'success',
                                    text: "File yang Anda pilih berhasil terhapus"
                                })
                                initCart();
                            })
                        }
                    })

                    $(".swal2-warning").addClass("orange");

                });

                $(document).on('click', '.btn-mobile-payment', function(e){
                    e.preventDefault();

                    $('#form-payment').submit();
                })
            }
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
