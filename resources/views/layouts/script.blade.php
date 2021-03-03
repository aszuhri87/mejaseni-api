<script>
    var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";

</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>
    var KTAppSettings = {
        "breakpoints": {
            "sm": 576,
            "md": 768,
            "lg": 992,
            "xl": 1200,
            "xxl": 1400
        },
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#3699FF",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#E4E6EF",
                    "dark": "#181C32"
                },
                "light": {
                    "white": "#ffffff",
                    "primary": "#E1F0FF",
                    "secondary": "#EBEDF3",
                    "success": "#C9F7F5",
                    "info": "#EEE5FF",
                    "warning": "#FFF4DE",
                    "danger": "#FFE2E5",
                    "light": "#F3F6F9",
                    "dark": "#D6D6E0"
                },
                "inverse": {
                    "white": "#ffffff",
                    "primary": "#ffffff",
                    "secondary": "#3F4254",
                    "success": "#ffffff",
                    "info": "#ffffff",
                    "warning": "#ffffff",
                    "danger": "#ffffff",
                    "light": "#464E5F",
                    "dark": "#ffffff"
                }
            },
            "gray": {
                "gray-100": "#F3F6F9",
                "gray-200": "#EBEDF3",
                "gray-300": "#E4E6EF",
                "gray-400": "#D1D3E0",
                "gray-500": "#B5B5C3",
                "gray-600": "#7E8299",
                "gray-700": "#5E6278",
                "gray-800": "#3F4254",
                "gray-900": "#181C32"
            }
        },
        "font-family": "Poppins"
    };

</script>
<!--end::Global Config-->

<!--begin::Global Theme Bundle(used by all pages)-->
<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<!--end::Global Theme Bundle-->

<!--begin::Page Vendors(used by this page)-->
<script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/1.0.3/numeral.min.js"></script>
<!--end::Page Vendors-->

<!--begin::Page Scripts(used by this page)-->
<script src="assets/js/pages/widgets.js"></script>
<!--end::Page Scripts-->

<script>
    var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }

    $('#kt_datepicker_1_modal').datepicker({
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        orientation: "bottom left",
        templates: arrows,
        format: 'd MM yyyy'
    });

    $('.datepicker').datepicker({
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        orientation: "bottom left",
        templates: arrows,
        format: 'd MM yyyy'
    });

    $('.timepicker').timepicker({
        minuteStep: 1,
        showSeconds: true,
        showMeridian: false
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        statusCode: {
            403: function() {
                window.location = '{{ url('admin/login') }}';
            },
            419: function() {
                window.location = '{{ url('admin/login') }}';
            }
        }
    });

    $(document).on('click', '.btn-delete-cart', function(event) {
        event.preventDefault();
        let url = $(this).attr('href');
        Swal.fire({
            title: 'Delete Item Cart?',
            text: "Deleted item cart will be permanently lost!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7F16A7',
            confirmButtonText: 'Yes, Delete',
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                    })
                    .done(function(res, xhr, meta) {
                        getCart();
                        toastr.success(res.message, 'Success')
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {});
            }
        })

        $('.swal2-title').addClass('justify-content-center')
    })

    function unescapeHtml(text) {
        return text
            .replace(/&amp;/g, "&")
            .replace(/&lt;/g, "<")
            .replace(/&gt;/g, ">")
            .replace(/&quot;/g, '"')
            .replace(/&#039;/g, "'");
    }

    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, '&quot;')
            .replace(/'/g, "&#039;");
    }

    function showModal(selector) {
        $('#' + selector).modal('show')
    }

    function hideModal(selector) {
        $('#' + selector).modal('hide')
    }

    function ellipsis_text(data, length) {
        if (data.length > length) {
            return `<span title="${data}">${data.substr( 0, length ) +'…'}</span>`
        } else {
            return data
        }
    }

    function btn_loading(action) {
        if (action == 'start') {
            $('.btn-loading').html('<div id="loading" class="mr-1"></div> Loading...');
            $('.btn-loading').addClass('d-flex align-items-center');
            $('.btn-loading').attr('disabled', true);
        } else {
            $('.btn-loading').html('Submit');
            $('.btn-loading').removeClass('d-flex align-items-center');
            $('.btn-loading').attr('disabled', false);
        }
    }

    function btn_loading_activate(action, id) {
        if (action == 'start') {
            $(`.btn-loading-activate-${id}`).html('<div id="loading" class="mr-1"></div> Loading...');
            $(`.btn-loading-activate-${id}`).addClass('d-flex align-items-center');
            $(`.btn-loading-activate-${id}`).attr('disabled', true);
        }
    }

    function btn_loading_profile(action) {
        if (action == 'start') {
            $('.btn-loading-profile').html('<div id="loading" class="mr-1"></div> Loading...');
            $('.btn-loading-profile').addClass('d-flex align-items-center');
            $('.btn-loading-profile').attr('disabled', true);
        } else {
            $('.btn-loading-profile').html('Save Change');
            $('.btn-loading-profile').removeClass('d-flex align-items-center');
            $('.btn-loading-profile').attr('disabled', false);
        }
    }

    function btn_loading_basic(action, text) {
        if (action == 'start') {
            $('.btn-loading-basic').html('<div id="loading" class="mr-1"></div> Loading...');
            $('.btn-loading-basic').attr('disabled', true);
        } else {
            $('.btn-loading-basic').html(text);
            $('.btn-loading-basic').attr('disabled', false);
        }
    }

    function btn_loading_class(id_name, action, text) {
        if (action == 'start') {
            $('#' + id_name).html('<div id="loading" class="mr-1"></div> Loading...');
            $('#' + id_name).attr('disabled', true);
        } else {
            $('#' + id_name).html(text);
            $('#' + id_name).attr('dissabled', false);
        }
    }

    function btn_loading_class_not_text(id_name, action, text) {
        if (action == 'start') {
            $('#' + id_name).html('<div id="loading" class="mr-1"></div>');
            $('#' + id_name).attr('disabled', true);
        } else {
            $('#' + id_name).html(text);
            $('#' + id_name).attr('dissabled', false);
        }
    }

    function btn_loading_exercise(action, text) {
        if (action == 'start') {
            $('.btn-loading-exercise').html('<div id="loading" class="mr-1"></div> Loading...');
            $('.btn-loading-exercise').attr('disabled', true);
        } else {
            $('.btn-loading-exercise').html(text);
            $('.btn-loading-exercise').attr('disabled', false);
        }
    }

    function btn_loading_master_lesson(action, text) {
        if (action == 'start') {
            $('.btn-loading-master-lesson').html('<div id="loading" class="mr-1"></div> Loading...');
            $('.btn-loading-master-lesson').attr('disabled', true);
        } else {
            $('.btn-loading-master-lesson').html(text);
            $('.btn-loading-master-lesson').attr('disabled', false);
        }
    }

    function btn_loading_rating(action) {
        if (action == 'start') {
            $('.btn-loading-rating').html('<div id="loading" class="mr-1"></div> Loading...');
            $('.btn-loading-rating').attr('disabled', true);
        } else {
            $('.btn-loading-rating').html('Kirim Review');
            $('.btn-loading-rating').attr('disabled', false);
        }
    }

    function btn_loading_reschedule(action) {
        if (action == 'start') {
            $('.btn-loading-reschedule').html('<div id="loading" class="mr-1"></div> Loading...');
            $('.btn-loading-reschedule').attr('disabled', true);
        } else {
            $('.btn-loading-reschedule').html('Reschedule');
            $('.btn-loading-reschedule').attr('disabled', false);
        }
    }

    function disable_action(class_name, action) {
        if (action == 'start') {
            $('#' + class_name).attr('disabled', true);
        } else {
            $('#' + class_name).attr('disabled', false);
        }
    }

    window.searchDelay = function(callback, ms) {
        var timer = 0;
        return function() {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function() {
                callback.apply(context, args);
            }, ms || 0);
        };
    }

</script>
@if (Auth::guard('student')->check())
    <script>
        function getCart() {
            $.ajax({
                    url: `{{ url('student/get-cart') }}/{{ Auth::guard('student')->user()->id }}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    if (res.status == 200) {
                        let element = '';
                        let total_price = 0;
                        if (res.data.length > 0) {
                            $.each(res.data, function(index, data) {
                                if (data.classroom_price) {
                                    total_price += parseInt(data.classroom_price);
                                } else if (data.master_lesson_price) {
                                    total_price += parseInt(data.master_lesson_price);
                                } else if (data.session_video_price) {
                                    total_price += parseInt(data.session_video_price);
                                } else if (data.theory_price) {
                                    total_price += parseInt(data.theory_price);
                                }

                                element += `
                            <div class="py-8">
                                <div class="row">
                                    <div class="col-1">
                                        <label class="checkbox">`;
                                if (data.theory_id) {
                                    element +=
                                        `<input type="checkbox" name="cart_id[]" value="${data.theory_id}" checked=true/>`
                                } else if (data.classroom_id) {
                                    element +=
                                        `<input type="checkbox" name="cart_id[]" value="${data.classroom_id}" checked=true/>`
                                } else if (data.session_video_id) {
                                    element +=
                                        `<input type="checkbox" name="cart_id[]" value="${data.session_video_id}" checked=true/>`
                                }
                                element += `
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-3" style="padding-right:0 !important">
                                        <a href="javascript:void(0)">`;
                                if (data.theory_id) {
                                    element +=
                                        `<img src="{{ asset('assets/images/pdf-file-extension.png') }}" class="rounded" width="80px" height="80px">`
                                } else if (data.classroom_id) {
                                    element +=
                                        `<img src="${data.image_classroom}" class="rounded" width="80px" height="80px">`
                                } else if (data.session_video_id) {
                                    element +=
                                        `<img src="{{ asset('assets/images/thumbnail.png') }}" class="rounded" width="80px" height="80px">`
                                }
                                element += `
                                        </a>
                                    </div>
                                    <div class="col-7">
                                        <div class="d-flex flex-column mr-2">`;

                                if (data.classroom_name) {
                                    if (data.package_type == 1) {
                                        element +=
                                            `<span class="label label-light-warning label-inline col-6">Special Class</span>`;
                                    } else if (data.package_type == 2) {
                                        element +=
                                            `<span class="label label-light-warning label-inline col-6">Regular Class</span>`;
                                    }
                                    element += `
                                                <a href="javascript:void(0)" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">${data.classroom_name}</a>
                                                <div class="d-flex align-items-center mt-2">
                                                    <span class="font-weight-bold mr-1 text-primary-75 font-size-lg text-primary">Rp. ${numeral(data.classroom_price).format('0,0')}</span>
                                                </div>
                                            `;
                                } else if (data.session_video_id) {
                                    element += `
                                                <span class="label label-light-warning label-inline col-6">Video Course</span>
                                                <a href="javascript:void(0)" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">${data.session_video_name}</a>
                                                <div class="d-flex align-items-center mt-2">
                                                    <span class="font-weight-bold mr-1 text-primary-75 font-size-lg text-primary">Rp. ${numeral(data.session_video_price).format('0,0')}</span>
                                                </div>
                                            `;
                                } else if (data.theory_id) {
                                    element += `
                                                <span class="label label-light-warning label-inline col-6">Theory Course</span>
                                                <a href="javascript:void(0)" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">${data.theory_name}</a>
                                                <div class="d-flex align-items-center mt-2">
                                                    <span class="font-weight-bold mr-1 text-primary-75 font-size-lg text-primary">Rp. ${numeral(data.theory_price).format('0,0')}</span>
                                                </div>
                                            `;
                                }

                                element += `
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <a href="{{ url('student/delete-cart') }}/${data.id}" class="btn-delete-cart">
                                            <span class="svg-icon svg-icon-danger svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>
                                                    <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                                </g>
                                            </svg></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="separator separator-solid"></div>
                            `;
                            });
                            $('.cart').html(element);
                        }
                        $('.total-price').html(`Rp. ${numeral(total_price).format('0,0')}`);
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
        }

        $('#form-pay').submit(function(event) {
            event.preventDefault();
            let check = 0;
            $('input[type=checkbox]').each(function() {
                if (this.checked) {
                    check++;
                }
            });
            if (check == 0) {
                return toastr.error('No cart checkout', 'Failed')
            }
        })
        getCart();

    </script>
@endif
