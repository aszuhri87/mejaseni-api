<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, calendar, calendar_regular, calendar_special, calendar_master_lesson, init_classroom, init_table_request;

            $(document).ready(function() {
                formSubmit();
                initAction();
                initCalendarReguler();
                initCalendarSpecial();
                initCalendarMasterLesson();
                totalClassStudent();
                initCoachList();
                studentRating();
                getClass();
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

                $(document).on('click','.tab-schedule-request',function(event){
                    event.preventDefault();
                    table_request();
                });

                $(document).on('click','.see-all',function(event){
                    $(".class-owned").removeClass("fade-out-up");
                    $(".class-owned").addClass("fade-in-down");
                    $(".class-owned").toggle();
                });

                $(document).on('click','.class-owned__item',function(event){
                    event.preventDefault();
                    let image = $(this).find('.class-image').attr('src');
                    let name = $(this).find('.class-name').text();
                    let subtraction = $(this).data('subtraction');
                    let is_rating = $(this).data('is_rating');
                    let classroom_id = $(this).data('classroom_id');

                    $('.class-owned').removeClass('fade-in-down');
                    $('.class-owned').addClass('fade-out-up');
                    $('.class-owned').css('display', 'none');
                    $('#class-name-selected').html(name);
                    $('#class-image-selected').attr('src', image);
                    $('#last-meeting').html(subtraction);
                    $('#rating-classroom-id').val(classroom_id);

                    if(subtraction == 0){
                        if(is_rating){
                            $('#rating-class').hide();
                        }
                        else{
                            $('#rating-class').show();
                        }
                    }
                });

                $(document).on('click','.btn-request-schedule', function(event){
                    event.preventDefault();

                    get_classroom();

                    $('#select-frequency').val('');
                    $('#time-place').empty();

                    showModal('modal-request-schedule');
                });

                $(document).on('change','#select-frequency',function(event){
                    event.preventDefault();

                    let element = '';

                    let options = `<option value="Monday">Senin</option>
                        <option value="Tuesday">Selasa</option>
                        <option value="Wednesday">Rabu</option>
                        <option value="Thursday">Kamis</option>
                        <option value="Friday">Jumat</option>
                        <option value="Saturday">Sabtu</option>
                        <option value="Sunday">Minggu</option>`;

                    if($(this).val()){
                        let value = parseInt($(this).val());
                        for (let index = 0; index < value; index++) {
                            element += `<div class="row">
                                <div class="col-12">
                                    <h6>Sesi ${index + 1}</h6>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Hari<span class="text-danger">*</span></label>
                                        <select name="day[${index}]" class="form-control">
                                            ${options}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Waktu Mulai<span class="text-danger">*</span></label>
                                        <input type="text" name="time[${index}]" class="form-control timepicker" required placeholder="Time" style="width: 100% !important">
                                    </div>
                                </div>
                                <div class="col-12 mt-0 pt-0">
                                    <hr>
                                </div>
                            </div>`;
                        }

                        $('#time-place').html(element);

                        $('.timepicker').timepicker({
                            minuteStep: 1,
                            showSeconds: true,
                            showMeridian: false
                        });
                    }else{
                        $('#time-place').html('');
                    }
                });

                $(document).on('click','#btn-reschedule',function(event){
                    event.preventDefault();

                    $.ajax({
                        url: `{{ url('student/schedule/regular-class/list-reschedule') }}`,
                        type: `POST`,
                        data: {
                            student_classroom_id: $(this).data('student_classroom_id'),
                            coach_schedule_id: $(this).data('coach_schedule_id'),
                        },
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            if(res.data.length > 0){
                                element = ``;

                                $.each(res.data, function(index, data){
                                    element += `
                                        <li class="list-group-item hover-list">
                                            <label class="radio radio-lg d-flex align-items-center">
                                            <input type="radio" name="new_coach_schedule_id" value="${data.id}" required/>
                                            <span class="mr-2"></span>
                                            <div class="w-100">
                                                <table class="table table-borderless" width="100%">
                                                    <tr>
                                                        <td>
                                                            <label>Title</label>
                                                            <h5>${data.title}</h5>
                                                        </td>
                                                        <td>
                                                            <label>Date</label>
                                                            <h5>${moment(data.start).format('DD MMMM YYYY')}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label>Coach</label>
                                                            <h5>${data.coach_name}</h5>
                                                        </td>
                                                        <td>
                                                            <label>Date</label>
                                                            <h5>${moment(data.start).format('HH:mm')}</h5>
                                                        </td>
                                                    </tr>
                                                </table>

                                            </div>
                                        </li>
                                    `;
                                });

                                $('#old_coach_schedule_id').val(res.student_schedule.coach_schedule_id);
                                $('#schedule_classroom_id').val(res.student_schedule.classroom_id);
                                $('#confirm-reschedule').show();
                                $('#ul-list-schedule').html(element);
                            }else{
                                $('#confirm-reschedule').hide();
                                $('#ul-list-schedule').html(`
                                    <li class="list-group-item text-center">
                                        <strong>Jadwal tidak tersedia.</strong>
                                    </li>
                                `);
                            }
                            showModal('modal-reschedule');
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
                            if($('#package_type').val()==1){
                                initCalendarSpecial();
                            }
                            else{
                                initCalendarReguler();
                            }
                            hideModal('modal-booking');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_basic('stop','Konfirmasi Booking')
                    });
                });

                $('#form-cancel-schedule').submit(function(event){
                    event.preventDefault();

                    btn_loading_cancel_schedule('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            toastr.success(res.message, 'Success');
                            if($('#package_type').val()==1){
                                initCalendarSpecial();
                            }
                            else{
                                initCalendarReguler();
                            }
                            hideModal('modal-cancel-schedule');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_cancel_schedule('stop')
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

                $('#form-reschedule').submit(function(event){
                    event.preventDefault();

                    btn_loading_cancel_schedule('start')
                    $.ajax({
                        url: `{{url('student/schedule/regular-class/new-reschedule')}}`,
                        type: 'POST',
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            toastr.success(res.message, 'Success');
                            if($('#package_type').val()==1){
                                initCalendarSpecial();
                            }
                            else{
                                initCalendarReguler();
                            }
                            hideModal('modal-reschedule');
                            hideModal('modal-cancel-schedule');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_cancel_schedule('stop')
                    });
                });

                $('#form-request-schedule').submit(function(event){
                    event.preventDefault();

                    btn_loading_basic('start')
                    $.ajax({
                        url: "{{url('student/schedule/request')}}",
                        type: 'POST',
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            $('#select-frequency').val('');
                            $('#time-place').empty();
                            table_request()

                            hideModal('modal-request-schedule');
                            toastr.success(res.message, 'Success')
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_basic('stop','Submit')
                    });
                });
            },
            availableSchedule = (id) => {
                $.ajax({
                    url: `{{ url('student/schedule/available-schedule') }}/`+id,
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
                        let check_date = moment(moment(info.event.extendedProps.tanggal).format('YYYY-MM-DD HH:mm:ss')).isAfter(moment().add(6, 'h').format('YYYY-MM-DD HH:mm:ss'));

                        if(moment(moment(info.event.extendedProps.tanggal).format('YYYY-MM-DD HH:mm:ss')).isAfter(moment().format('YYYY-MM-DD HH:mm:ss'))){
                            if(info.event.extendedProps.status == 3){
                                let coach_schedule_id = info.event.extendedProps.coach_schedule_id
                                $('#package_type').val(info.event.extendedProps.package_type);

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
                                            showModal('modal-booking');
                                        }
                                        else{
                                            toastr.error('Batas Booking Sudah Maksimal', 'Failed')
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
                                let coach_schedule_id = info.event.extendedProps.coach_schedule_id;

                                $('#package_type').val(info.event.extendedProps.package_type);
                                $('#btn-reschedule').attr('data-student_classroom_id',info.event.extendedProps.student_classroom_id);
                                $('#btn-reschedule').attr('data-coach_schedule_id',info.event.extendedProps.coach_schedule_id);

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

                                        $('#form-cancel-schedule').trigger('reset');
                                        $('#form-cancel-schedule').attr('action',`{{ url('student/schedule/special-class/reschedule') }}`)
                                        $('#form-cancel-schedule').attr('method',`POST`);

                                        if(check_date){
                                            if(data.status_reschedule == 1){
                                                $('#btn-reschedule').hide();
                                                $('#btn-cancel-reschedule').show();
                                                $('.show-hide').removeAttr('style');
                                            }
                                            else{
                                                $('#btn-reschedule').hide();
                                                $('#btn-cancel-reschedule').hide();
                                                $('.show-hide').attr('style','display:none !important');
                                            }
                                        }else{
                                            $('#btn-cancel-reschedule').hide();
                                            $('#btn-reschedule').hide();
                                            $('.show-hide').attr('style','display:none !important');
                                        }

                                        showModal('modal-cancel-schedule');
                                    }
                                })
                                .fail(function(res, error) {
                                    toastr.error(res.responseJSON.message, 'Failed')
                                })
                                .always(function() {

                                });

                            }


                        }
                    }
                });

                renderSpecial();

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
                            if(data.show){
                                calendar_special.addEvent({
                                    "coach_schedule_id": data.id,
                                    "classroom_id": data.classroom_id,
                                    "title": data.title,
                                    "start": data.start,
                                    "className": `bg-${data.color} border-${data.color} p-1 ${data.color == 'primary' ? 'text-white' : ''}`,
                                    "allDay": false,
                                    "status": data.status,
                                    "tanggal" : data.start,
                                    "student_classroom_id" : data.student_classroom_id,
                                    "package_type" : data.package_type
                                });
                            }
                        });
                        calendar_special.render();
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
                    contentHeight: 800,
                    eventClick: function(info) {
                        let check_date = moment(moment(info.event.extendedProps.tanggal).format('YYYY-MM-DD HH:mm:ss')).isAfter(moment().add(6, 'h').format('YYYY-MM-DD HH:mm:ss'));

                        if(moment(moment(info.event.extendedProps.tanggal).format('YYYY-MM-DD HH:mm:ss')).isAfter(moment().format('YYYY-MM-DD HH:mm:ss'))){

                            if(info.event.extendedProps.status == 3){
                                let coach_schedule_id = info.event.extendedProps.coach_schedule_id
                                $('#package_type').val(info.event.extendedProps.package_type);

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
                                            showModal('modal-booking');
                                        }
                                        else{
                                            toastr.error('Batas Booking Sudah Maksimal', 'Failed')
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
                                let coach_schedule_id = info.event.extendedProps.coach_schedule_id;

                                $('#package_type').val(info.event.extendedProps.package_type);
                                $('#btn-reschedule').attr('data-student_classroom_id',info.event.extendedProps.student_classroom_id);
                                $('#btn-reschedule').attr('data-coach_schedule_id',info.event.extendedProps.coach_schedule_id);

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

                                        $('#form-cancel-schedule').trigger('reset');
                                        $('#form-cancel-schedule').attr('action',`{{ url('student/schedule/regular-class/reschedule') }}`)
                                        $('#form-cancel-schedule').attr('method',`POST`);

                                        if(check_date){
                                            if(data.status_reschedule == 1){
                                                $('#btn-reschedule').hide();
                                                $('#btn-cancel-reschedule').show();
                                                $('.show-hide').removeAttr('style');
                                            }
                                            else{
                                                $('#btn-reschedule').hide();
                                                $('#btn-cancel-reschedule').hide();
                                                $('.show-hide').attr('style','display:none !important');
                                            }
                                        }else{
                                            $('#btn-cancel-reschedule').hide();
                                            $('#btn-reschedule').hide();
                                            $('.show-hide').attr('style','display:none !important');
                                        }

                                        showModal('modal-cancel-schedule');
                                    }
                                })
                                .fail(function(res, error) {
                                    toastr.error(res.responseJSON.message, 'Failed')
                                })
                                .always(function() {

                                });


                            }
                        }
                    }
                });

                renderRegular();

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
                            if(data.show == 1){
                                calendar_regular.addEvent({
                                    "coach_schedule_id": data.id,
                                    "classroom_id": data.classroom_id,
                                    "title": data.title,
                                    "start": data.start,
                                    "className": `bg-${data.color} border-${data.color} p-1 ${data.color == 'primary' ? 'text-white' : ''}`,
                                    "allDay": false,
                                    "status": data.status,
                                    "tanggal" : data.start,
                                    "student_classroom_id" : data.student_classroom_id,
                                    "package_type" : data.package_type
                                });
                            }
                        });
                        calendar_regular.render();
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
                        let platform_link = info.event.extendedProps.platform_link;
                        if(moment(moment(datetime).format('YYYY-MM-DD')).isSameOrBefore(moment().format('YYYY-MM-DD'))){
                            if(is_buy){
                                $('#to-class').show();
                                $('#link').attr('href',platform_link);
                                $('#link').attr('target','_blank');
                            }else{
                                $('#to-class').hide();
                            }
                        }else{
                            $('#to-class').hide();
                        }
                        $('#master-lesson-id').val(id);
                        $('#master-lesson-title').html(name);
                        $('#poster').attr('src',`${poster}`);
                        $('#price').html(`Rp. ${numeral(price).format('0,0')}`);
                        $('#date').html(`<h5 class="text-muted">${moment(datetime).format('DD MMMM YYYY')}</h5>`);
                        $('#time').html(`<h5 class="text-muted">${moment(datetime).format('HH:mm')}</h5>`);
                        $('#total-booking').html(`<h5 class="text-muted">${total_booking}/${slot} booking</h5>`);
                        $('#description').html(description);

                        if(total_booking < slot){
                            if(moment(datetime).isSameOrAfter(moment().format('YYYY-MM-DD HH:mm:ss'))){

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
                        }
                        else{
                            if(is_buy){
                                $('#btn-booking-master-lesson').hide();
                                showModal('modal-booking-master-lesson');
                            }else{
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
                    }
                });

                renderMasterLesson();
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
                                "platform_link" : data.platform_link,
                            });
                        });
                        calendar_master_lesson.render();
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
                        let element = ``;
                        $.each(res.data, function(index, data){
                            element +=`
                                <tr>
                                    <th>
                                        <div class="d-flex">
                                            <div>
                                                <img src="${data.image}" class="rounded-circle" width="50px" height="50px">
                                            </div>
                                            <div class="ml-3">
                                                <strong>${data.name}</strong><br>
                                                <span class="text-muted">${data.email}</span>
                                            </div>
                                        </div>
                                    </th>
                                    <td>${data.phone}</td>

                                    <td>`;
                                    let min_star = 5 - parseInt(data.rating);
                                    for(let i=0; i<parseInt(data.rating); i++){
                                        element+=`
                                        <span class="svg-icon svg-icon-warning svg-icon-sm"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                        `
                                    }
                                    for(let i=0; i<parseInt(min_star); i++){
                                        element+=`
                                        <span class="svg-icon svg-icon-sm"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                        `
                                    }
                                        element+=`
                                    </td>
                                </tr>
                            `;
                        });

                        $('#table-body').html(element);
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
                        if(res.data.length>0){
                            if(res.data[0].star == null){
                                $('#total-rating').html(0);
                            }
                            else{
                                $('#total-rating').html(res.data[0].star);
                            }
                        }
                        else{
                            $('#total-rating').html(0);
                        }
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            getClass = () => {
                $.ajax({
                    url: `{{ url('student/my-class/class-active') }}/{{Auth::guard('student')->user()->id}}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        if(res.data.length > 0){

                            let element = ``;
                            let selected = ``;
                            $.each(res.data, function(index, data){
                                if(index == 0){
                                    selected = `
                                    <img id="class-image-selected" class="w-100" src="${data.image}" alt="">
                                    <div class="h-100 class-owned-overlay">
                                        <h5 id="class-name-selected">${data.classroom_name}</h5>
                                    </div>
                                    `;

                                    $('.class-owned-selected').html(selected);
                                    $('#last-meeting').html(data.subtraction);
                                    $('#rating-classroom-id').val(data.classroom_id);

                                }
                                element += `
                                    <li class="class-owned__item" data-subtraction="${data.subtraction}" data-classroom_id="${data.classroom_id}" data-is_rating="${data.is_rating}">
                                        <img class="w-100 class-image" src="${data.image}"
                                            alt="">
                                        <div class="class-owned-overlay h-100">
                                            <h5 class="class-name">${data.classroom_name}</h5>
                                        </div>
                                    </li>
                                `;
                            });
                            $('#list-class-active').html(element);
                            $('.see-all').html(`<img src="{{asset('cms/assets/img/svg/layers.svg')}}" class="mr-2" alt=""> See All`);
                            $('.see-all').show();
                        }
                        else{
                            $('.see-all').show();
                        }
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            get_classroom = (category_id, sub_category_id, select_id) => {
                if(init_classroom){
                    init_classroom.destroy();
                }

                $.ajax({
                    url: '{{url('student/schedule/request/classroom')}}',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="" data-subtraction="">Select Class</option>`

                    $.each(res.data, function(index, data) {
                        element += `<option value="${data.id}" data-subtraction="${data.subtraction}">${data.name}</option>`;
                    });

                    $('#select-classroom').html(element);
                })
            },
            table_request = () => {
                if(init_table_request){
                    init_table_request.draw(false);
                }else{
                    init_table_request = $('#init-table-request').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                        ajax: {
                            type: 'POST',
                            url: "{{ url('student/schedule/request/dt') }}",
                        },
                        columns: [
                            { data: 'DT_RowIndex' },
                            { data: 'classroom' },
                            { data: 'datetime' },
                            { data: 'status' },
                        ],
                        columnDefs: [
                            {
                                targets: 0,
                                searchable: false,
                                orderable: false,
                                className: "text-center"
                            },
                            {
                                targets: 2,
                                data: "datetime",
                                render: function(data, type, full, meta){
                                    return `${moment(data).format('DD MMMM YYYY, HH:mm')}`;
                                }
                            },
                            {
                                targets: 3,
                                data:"status",
                                render: function(data, type, full, meta){
                                    return `
                                        <div class="d-flex flex-column font-weight-bold">
                                            <p class="mb-1 font-size-lg text-${full.status_color}">${data}</p>
                                        </div>
                                    `;
                                }
                            }
                        ],
                        order: [[1, 'asc']],
                        searching: true,
                        paging:true,
                        lengthChange:false,
                        bInfo:true,
                        dom: '<"datatable-header"><tr><"datatable-footer"ip>',
                        language: {
                            search: '<span>Search:</span> _INPUT_',
                            searchPlaceholder: 'Search.',
                            lengthMenu: '<span>Show:</span> _MENU_',
                            processing: '<div class="d-flex justify-content-center align-items-center"><div class="mr-1 my-spinner-loading"></div>Loading...</div>',
                        },
                    });
                }
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
