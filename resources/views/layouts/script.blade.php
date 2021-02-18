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
        format:'d MM yyyy'
    });

    $('.datepicker').datepicker({
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        orientation: "bottom left",
        templates: arrows,
        format:'d MM yyyy'
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
            403: function(){
                window.location = '{{url('admin/login')}}';
            },
            419: function(){
                window.location = '{{url('admin/login')}}';
            }
        }
    });

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
        $('#'+selector).modal('show')
    }

    function hideModal(selector) {
        $('#'+selector).modal('hide')
    }

    function ellipsis_text(data, length){
        if(data.length > length){
            return `<span title="${data}">${data.substr( 0, length ) +'…'}</span>`
        }else{
            return data
        }
    }

    function btn_loading(action) {
        if(action == 'start'){
            $('.btn-loading').html('<div id="loading" class="mr-1"></div> Loading...');
            $('.btn-loading').addClass('d-flex align-items-center');
            $('.btn-loading').attr('disabled',true);
        }else{
            $('.btn-loading').html('Submit');
            $('.btn-loading').removeClass('d-flex align-items-center');
            $('.btn-loading').attr('disabled',false);
        }
    }

    function btn_loading_activate(action,id) {
        if(action == 'start'){
            $(`.btn-loading-activate-${id}`).html('<div id="loading" class="mr-1"></div> Loading...');
            $(`.btn-loading-activate-${id}`).addClass('d-flex align-items-center');
            $(`.btn-loading-activate-${id}`).attr('disabled',true);
        }
    }

    function btn_loading_profile(action) {
        if(action == 'start'){
            $('.btn-loading-profile').html('<div id="loading" class="mr-1"></div> Loading...');
            $('.btn-loading-profile').addClass('d-flex align-items-center');
            $('.btn-loading-profile').attr('disabled',true);
        }else{
            $('.btn-loading-profile').html('Save Change');
            $('.btn-loading-profile').removeClass('d-flex align-items-center');
            $('.btn-loading-profile').attr('disabled',false);
        }
    }

    window.searchDelay = function (callback, ms) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }
</script>
