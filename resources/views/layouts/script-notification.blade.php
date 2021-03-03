<script src="{{ asset('/js/app.js') }}"></script>
<script type="text/javascript">
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
                @if(Auth::guard('student')->check())
                    Echo.channel('laravel_database_student-notification')
                        .listen('.student.notification.{{Auth::guard('student')->user()->id}}', e => {
                            initNotification();
                        })
                @elseif(Auth::guard('coach')->check())
                    Echo.channel('laravel_database_coach-notification')
                        .listen('.coach.notification.{{Auth::guard('coach')->user()->id}}', e => {
                            initNotification();
                        })
                @else
                    Echo.channel('laravel_database_admin-notification')
                        .listen('.admin.notification', e => {
                            initNotification();
                        })
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

