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
                            initCalendarReguler();
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

                    btn_loading_reschedule('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            toastr.success(res.message, 'Success');
                            initCalendarSpecial();
                            initCalendarReguler();
                            hideModal('modal-reschedule');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_reschedule('stop')
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
                        if(total_booking < slot){
                            if(moment(datetime).isSameOrAfter(moment().format('YYYY-MM-DD HH:mm:ss'))){

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
                                "platform_link" : data.platform_link,
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
                                    <td>${data.class_active} Schedule</td>
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
                        if(res.data[0].star == null){
                            $('#total-rating').html(0);
                        }
                        else{
                            $('#total-rating').html(res.data[0].star);
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
