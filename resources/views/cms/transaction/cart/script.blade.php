<script src="assets/js/script.js"></script>

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
                                    <h5>${data.name}</h5>
                                    <div class="badge-class mt-2 text-center">${data.type}</div>
                                </div>
                                <div
                                    class="col-11 col-md-12 col-xl-4 text-left text-xl-right mt-3 mt-md-4 mt-xl-0 mt-md-0 mx-4 mx-md-0 px-4 pl-3 pl-xl-0">
                                    <div class="row row-center-spacebetween">
                                        <div class="col-9 col-md-10">
                                            <h5 style="overflow-wrap: break-word;">Rp. ${data.price}</h5>
                                        </div>
                                        <div class="col-3 col-md-2">
                                            <div class="delete-item" data-id="${data.id}">
                                                <a href="#">
                                                    <img src="assets/img/svg/Trash.svg" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-line mx-4 mb-4"></div>`;

                        total += parseInt(data.price)
                    })

                    $('#list-place').html(element);
                    $('.grand-total').html('Rp. '+total);
                });
            },
            initAction = () => {
                $(document).on('click',".delete-item", function (e) {
                    event.preventDefault();

                    let id = $(this).attr('data-id');
                    console.log(id);
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
                                url: "{{url('student-cart')}}/"+id,
                                type: 'DELETE',
                                dataType: 'json',
                            }).done(function(res, xhr, meta){
                                swalWithBootstrapButtons.fire({
                                    title: 'Terhapus!',
                                    padding: '2em',
                                    icon: 'success',
                                    text: "File yang Anda pilih berhasil terhapus"
                                })
                            })
                        }
                    })

                    $(".swal2-warning").addClass("orange");

                });
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
