<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<script>
    tippy('.copied', {
        content: 'Berhasil Disalin!',
        animation: 'scale',
        placement: 'bottom',
        trigger: 'click',
        hideOnClick: false,
        onShow(instance) {
            setTimeout(() => {
                instance.hide();
            }, 1000);
        }
    });

    function copyPaymentNumber() {
        var copyText = document.getElementById("paymentNumber");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
    };
</script>

<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {

            $(document).ready(function () {
                AOS.init();
            });

            const initAction = () => {

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
