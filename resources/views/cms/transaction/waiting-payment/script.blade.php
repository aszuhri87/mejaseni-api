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
                initAction();
            });

            const initAction = () => {
                $(document).on('click', '.btn-payment', function(event){
                    event.preventDefault()

                    let url = $(this).attr('href');

                    popup({url: url, title: 'Mejaseni', w: 600, h: 500})
                })

                $(document).on('click', '.btn-check-payment', function(event){
                    event.preventDefault()

                    let url = $(this).attr('href');

                    $.ajax({
                        url: url,
                        type: 'GET',
                    }).done(function(res, xhr, meta){
                        if(res.data){
                            window.location.reload();
                        }else{
                            alert('Pembayaran masih aktif, silakan lakukan pembayaran.');
                        }
                    }).fail(function(res, error) {
                        alert('Proses gagal, silakan coba kembali.');
                    });
                })
            },
            popup = ({url, title, w, h}) => {

                const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
                const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;

                const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
                const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

                const systemZoom = width / window.screen.availWidth;
                const left = (width - w) / 2 / systemZoom + dualScreenLeft
                const top = (height - h) / 2 / systemZoom + dualScreenTop
                const newWindow = window.open(url, title,
                `
                    scrollbars=yes,
                    width=${w / systemZoom},
                    height=${h / systemZoom},
                    top=${top},
                    left=${left}
                `
                )

                if (window.focus) newWindow.focus();
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

<script src="https://cdn.socket.io/3.1.3/socket.io.min.js"></script>
<script>
    const server_url = "https://client.socket.var-x.id";

    const socket = io(server_url, {
        auth: {
            token: "{{config('socket.token')}}",
        },
        query: {
            user_id: "{{Auth::guard('student')->user()->id}}"
        }
    });

    socket.on("{{config('socket.token')}}_payment_{{$transaction->id}}", function (data) {
        location.reload();
    });
</script>
