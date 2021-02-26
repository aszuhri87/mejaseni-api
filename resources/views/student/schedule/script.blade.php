<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, calendar, calendar_regular, calendar_special;

            $(document).ready(function() {
                formSubmit();
                initAction();
                initCalendarReguler();
                initCalendarSpecial();
                totalClassStudent();
            });

            const initAction = () => {
                $(document).on('click','.tab-regular',function(event){
                    event.preventDefault();
                    calendar_regular.render();
                });

                $(document).on('click','.tab-special',function(event){
                    event.preventDefault();
                    calendar_special.render();
                });

                $(document).on('click','.tab-master-lesson',function(event){
                    event.preventDefault();

                });
            },
            formSubmit = () => {
                $('#form-booking').submit(function(event){
                    event.preventDefault();

                    btn_loading_basic('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            toastr.success(res.message, 'Success');
                            initCalendarSpecial();
                            hideModal('modal-booking');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_basic('stop')
                    });
                });

                $('#form-reschedule').submit(function(event){
                    event.preventDefault();

                    btn_loading_basic('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            toastr.success(res.message, 'Success');
                            initCalendarSpecial();
                            hideModal('modal-reschedule');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_basic('stop')
                    });
                });
            },
            totalClassStudent = () => {
                $.ajax({
                    url: `{{ url('student/schedule/get-total-class') }}/{{Auth::guard('student')->user()->id}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        $('#total-class').html(res.data);
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            initCalendarSpecial = () => {
                var element = document.getElementById('calendar-special');
                calendar_special = new FullCalendar.Calendar(element, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,list'
                    },
                    locale: 'ind',
                    timeZone: 'local',
                    initialView: 'dayGridMonth',
                    eventColor: '#fff',
                    editable: true,
                    selectable: true,
                    eventClick: function(info) {
                        if(info.event.extendedProps.status == 3){
                            let coach_schedule_id = info.event.extendedProps.coach_schedule_id
                            showModal('modal-booking');
                            $.ajax({
                                url: `{{ url('student/schedule/special-class') }}/${coach_schedule_id}`,
                                type: `GET`,
                            })
                            .done(function(res, xhr, meta) {
                                if(res.status == 200){
                                    let data = res.data;
                                    if(data.remaining > 0){
                                        $('#booking-classroom-name').html(data.title);
                                        $('#booking-date').html(`${moment(data.start).format('DD MMMM YYYY')}`);
                                        $('#booking-time').html(`${moment(data.start).format('H:mm')}`);
                                        $('#booking-coach').html(data.coach_name);
                                        $('#booking-remaining').html(data.remaining);

                                        $('#coach_schedule_id').val(data.id);
                                        $('#classroom_id').val(data.classroom_id);

                                        $('#form-booking').trigger('reset');
                                        $('#form-booking').attr('action',`{{ url('student/schedule/special-class/booking') }}`)
                                        $('#form-booking').attr('method',`POST`);
                                    }
                                    else{
                                        toastr.error('Batas Pesanan Sudah Maksimal', 'Failed')
                                    }
                                }
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() {

                            });
                        }
                        else if(info.event.extendedProps.status == 2){
                            if(moment(moment(info.event.extendedProps.tanggal).format('YYYY-MM-DD')).isAfter(moment().format('YYYY-MM-DD'))){
                                let coach_schedule_id = info.event.extendedProps.coach_schedule_id
                                $('#btn-reschedule').show();
                                showModal('modal-reschedule');
                                $.ajax({
                                    url: `{{ url('student/schedule/special-class') }}/${coach_schedule_id}`,
                                    type: `GET`,
                                })
                                .done(function(res, xhr, meta) {
                                    if(res.status == 200){
                                        let data = res.data;
                                        $('#reschedule-classroom-name').html(data.title);
                                        $('#reschedule-date').html(`${moment(data.start).format('DD MMMM YYYY')}`);
                                        $('#reschedule-time').html(`${moment(data.start).format('H:mm')}`);
                                        $('#reschedule-coach').html(data.coach_name);
                                        $('#media-conference').html(data.platform_name);

                                        $('#reschedule_coach_schedule_id').val(data.id);
                                        $('#reschedule_classroom_id').val(data.classroom_id);

                                        $('#form-reschedule').trigger('reset');
                                        $('#form-reschedule').attr('action',`{{ url('student/schedule/special-class/reschedule') }}`)
                                        $('#form-reschedule').attr('method',`POST`);
                                    }
                                })
                                .fail(function(res, error) {
                                    toastr.error(res.responseJSON.message, 'Failed')
                                })
                                .always(function() {

                                });
                            }else{
                                $('#btn-reschedule').hide();
                            }
                        }
                    }
                });

                renderSpecial();
                calendar_special.render();
            },
            initCalendarReguler = () => {
                var element = document.getElementById('calendar-regular');
                $.ajax({
                    url: `{{ url('student/schedule/regular-class') }}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        calendar_regular = new FullCalendar.Calendar(element, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay,list'
                            },
                            locale: 'ind',
                            timeZone: 'Asia/Jakarta',
                            events: res.data
                        });
                        calendar_regular.render();
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            renderSpecial = () => {
                if(calendar_special){
                    calendar_special.removeAllEvents();
                }

                $.ajax({
                    url: `{{ url('student/schedule/special-class') }}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        $.each(res.data, function(index, data){
                            calendar_special.addEvent({
                                "coach_schedule_id": data.id,
                                "classroom_id": data.classroom_id,
                                "title": data.title,
                                "start": data.start,
                                "className": `bg-${data.color} border-${data.color} p-1 ${data.color == 'primary' ? 'text-white' : ''}`,
                                "allDay": false,
                                "status": data.status,
                                "tanggal" : data.start
                            });
                        })
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
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
