<script src="assets/js/script.js"></script>

<script type="text/javascript">
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

    var Layout = function () {
        var _componentLayout = function () {

            $(document).ready(function () {
                AOS.init();
            });
        };

        return {
            init: function () {
                _componentLayout();
            }
        }
    }();
    document.addEventListener('DOMContentLoaded', function () {
        Layout.init();
    });

</script>
