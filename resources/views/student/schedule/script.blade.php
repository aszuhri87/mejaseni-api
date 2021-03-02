<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, calendar, calendar_regular, calendar_special, calendar_master_lesson;

            $(document).ready(function() {
                formSubmit();
                initAction();
                initCalendarReguler();
                initCalendarSpecial();
                initCalendarMasterLesson();
                totalClassStudent();
                initCoachList();
                studentRating();
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
                    calendar_master_lesson.render();
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
                            initCalendarRegular();
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

                $('#form-booking-master-lesson').submit(function(event){
                    event.preventDefault();
                    let text = $('.btn-loading-master-lesson').html();

                    btn_loading_master_lesson('start',text)
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            toastr.success(res.message, 'Success');
                            initCalendarMasterLesson();
                            hideModal('modal-booking-master-lesson');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_master_lesson('stop',text)
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
                    editable: false,
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
            },
            initCalendarReguler = () => {
                var element = document.getElementById('calendar-regular');
                calendar_regular = new FullCalendar.Calendar(element, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,list'
                    },
                    locale: 'ind',
                    timeZone: 'local',
                    initialView: 'dayGridMonth',
                    eventColor: '#fff',
                    editable: false,
                    selectable: true,
                    eventClick: function(info) {
                        let check_date = moment(moment(info.event.extendedProps.tanggal).format('YYYY-MM-DD')).isAfter(moment().format('YYYY-MM-DD'));
                        if(check_date){
                            if(info.event.extendedProps.status == 3){
                                let coach_schedule_id = info.event.extendedProps.coach_schedule_id
                                showModal('modal-booking');
                                $.ajax({
                                    url: `{{ url('student/schedule/regular-class') }}/${coach_schedule_id}`,
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
                                            $('#form-booking').attr('action',`{{ url('student/schedule/regular-class/booking') }}`)
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
                                        url: `{{ url('student/schedule/regular-class') }}/${coach_schedule_id}`,
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
                                            $('#form-reschedule').attr('action',`{{ url('student/schedule/regular-class/reschedule') }}`)
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
                    }
                });

                renderRegular();
                calendar_regular.render();
            },
            renderRegular = () => {
                if(calendar_regular){
                    calendar_regular.removeAllEvents();
                }

                $.ajax({
                    url: `{{ url('student/schedule/regular-class') }}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        $.each(res.data, function(index, data){
                            calendar_regular.addEvent({
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
            initCalendarMasterLesson = () => {
                var element = document.getElementById('calendar-master-lesson');
                calendar_master_lesson = new FullCalendar.Calendar(element, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,list'
                    },
                    locale: 'ind',
                    timeZone: 'local',
                    initialView: 'dayGridMonth',
                    eventColor: '#fff',
                    editable: false,
                    selectable: true,
                    eventClick: function(info) {
                        let slot = info.event.extendedProps.slot;
                        let total_booking = info.event.extendedProps.total_booking;
                        let name = info.event.extendedProps.name;
                        let poster = info.event.extendedProps.poster;
                        let price = info.event.extendedProps.price;
                        let datetime = info.event.extendedProps.tanggal;
                        let id = info.event.extendedProps.master_lesson_id;
                        let description = info.event.extendedProps.description;
                        let is_buy = info.event.extendedProps.is_buy;

                        if(total_booking < slot){
                            $('#master-lesson-id').val(id);
                            $('#master-lesson-title').html(name);
                            $('#poster').attr('src',`${poster}`);
                            $('#price').html(`Rp. ${numeral(price).format('0,0')}`);
                            $('#date').html(`<h5 class="text-muted">${moment(datetime).format('DD MMMM YYYY')}</h5>`);
                            $('#time').html(`<h5 class="text-muted">${moment(datetime).format('HH:mm')}</h5>`);
                            $('#total-booking').html(`<h5 class="text-muted">${total_booking}/${slot} booking</h5>`);
                            $('#description').html(description);

                            $('#form-booking-master-lesson').trigger('reset');
                            $('#form-booking-master-lesson').attr('action',`{{ url('student/schedule/master-lesson/booking') }}/${id}`);
                            $('#form-booking-master-lesson').attr('method',`POST`);

                            if(is_buy){
                                $('#btn-booking-master-lesson').hide();
                            }else{
                                $('#btn-booking-master-lesson').show();
                            }

                            showModal('modal-booking-master-lesson');
                        }
                        else{
                            Swal.fire({
                                title: 'Master lesson class is full!',
                                text: "Participant quota has been fulfilled!",
                                icon: 'warning',
                                showCancelButton: false,
                                confirmButtonColor: '#7F16A7',
                                confirmButtonText: 'Close',
                            }).then(function (result) {
                                if (result.value) {

                                }
                            })
                            $('.swal2-title').addClass('justify-content-center')
                        }
                    }
                });

                renderMasterLesson();
                calendar_master_lesson.render();
            },
            renderMasterLesson = () => {
                if(calendar_master_lesson){
                    calendar_master_lesson.removeAllEvents();
                }

                $.ajax({
                    url: `{{ url('student/schedule/master-lesson') }}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        $.each(res.data, function(index, data){
                            calendar_master_lesson.addEvent({
                                "id": data.id,
                                "title": data.name,
                                "start": data.datetime,
                                "className": `bg-${data.color} border-${data.color} p-1 ${data.color == 'primary' ? 'text-white' : ''}`,
                                "allDay": false,
                                "status": data.status,
                                "tanggal" : data.datetime,
                                "slot" : data.slot,
                                "total_booking" : data.total_booking,
                                "poster" : data.poster,
                                "name" : data.name,
                                "price" : data.price,
                                "master_lesson_id" : data.id,
                                "description" : data.description,
                                "is_buy" : data.is_buy,
                            });
                        })
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            initCoachList = () => {
                $.ajax({
                    url: `{{ url('student/schedule/coach-list') }}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        $.each(res.data, function(index, data){

                        })
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            studentRating = () => {
                $.ajax({
                    url: `{{ url('student/schedule/student-rating') }}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        if(res.data.star == null){
                            $('#total-rating').html(0);
                        }
                        else{
                            $('#total-rating').html(res.data.star);
                        }
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
