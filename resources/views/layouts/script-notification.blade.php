<script src="https://cdn.socket.io/3.1.3/socket.io.min.js"></script>
<script>
    var Notification = function() {
        var _componentNotification = function(){

            $(document).ready(function() {
                initNotification();
                initSocket();
            });

            const initNotification = () => {
                $.ajax({
                    url: "{{url('notification')}}",
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    let element = '';

                    $.each(res.data, function(index, data){
                        let icon = '';

                        if(data.type == 1){
                            icon = 'flaticon-coins text-success';
                        }else if(data.type == 2){
                            icon = 'flaticon2-check-mark text-success';
                        }else{
                            icon = 'flaticon2-calendar-5 text-warning';
                        }

                        element += `<a href="javascript:void(0);" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-icon mr-2">
                                    <i class="${icon}"></i>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold">
                                        ${data.text}
                                    </div>
                                    <div class="text-muted">
                                        ${moment(data.datetime).format('DD MMMM, H:m')}
                                    </div>
                                </div>
                            </div>
                        </a>`;
                    })

                    $('#item-notification').html(element);
                })
            },
            initSocket = () => {
                const server_url = "https://client.socket.var-x.id";

                const socket = io(server_url, {
                    auth: {
                        token: "{{config('socket.token')}}",
                    },
                    query: {
                        @if(Auth::guard('student')->check())
                            user_id: "{{Auth::guard('student')->user()->id}}"
                        @elseif(Auth::guard('coach')->check())
                            user_id: "{{Auth::guard('coach')->user()->id}}"
                        @else
                            user_id: "{{Auth::guard('admin')->user()->id}}"
                        @endif
                    }
                });

                @if(Auth::guard('student')->check())
                    socket.on("{{config('socket.token')}}_notification_{{Auth::guard('student')->user()->id}}", function (data) {
                        initNotification();
                    });
                @elseif(Auth::guard('coach')->check())
                    socket.on("{{config('socket.token')}}_notification_{{Auth::guard('coach')->user()->id}}", function (data) {
                        initNotification();
                    });
                @else
                    socket.on("{{config('socket.token')}}_notification_admin", function (data) {
                        initNotification();
                    });
                @endif
            }
        };
        return {
            init: function(){
                _componentNotification();
            }
        }

    }();

    document.addEventListener('DOMContentLoaded', function() {
        Notification.init();
    });

</script>

