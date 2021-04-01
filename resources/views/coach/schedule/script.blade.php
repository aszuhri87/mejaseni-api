<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var calendar,
                arr_path = [],
                docsDropzone,
                init_classroom,
                init_platform,
                init_coach;

            $(document).ready(function() {
                initCalendar();
                initAction();
                formSubmit();
                get_platform();
                get_classroom_coach();
            });

            const initCalendar = () => {
                var calendarEl = document.getElementById('calendar');

                calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    locale: 'ind',
                    timeZone: 'local',
                    initialView: 'dayGridMonth',
                    eventColor: '#fff',
                    editable: true,
                    selectable: true,
                    height: 750,
                    select: function(info) {
                        let start = moment(info.start).format('DD MMMM YYYY');
                        let end = moment(new Date()).format('DD MMMM YYYY');

                        if(moment(start).isSameOrAfter(end)){
                            $('#form-schedule').trigger("reset");

                            $('.timepicker').val(moment(info.start).format('HH:mm:ss') == '00:00:00' ? moment().format('HH:mm:ss') : moment(info.start).format('HH:mm:ss'))
                            $('.datepicker-with-stardate').val(moment(info.start).format('D MMMM YYYY'))

                            $('#form-schedule').attr('action','{{url('coach/schedule')}}');
                            $('#form-schedule').attr('method','POST');

                            showModal('modal-schedule');
                        }
                    },
                    eventDrop: function(info) {
                        let old_start = moment(info.oldEvent.start).format('DD MMMM YYYY');
                        let end = moment(new Date()).format('DD MMMM YYYY');
                        let start = moment(info.event.start).format('DD MMMM YYYY');

                        if(moment(old_start).isSameOrBefore(end)){
                            info.revert();
                            return false;
                        }

                        if(moment(start).isAfter(end)){
                            Swal.fire({
                                title: 'Ubah Jadwal?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#7F16A7',
                                confirmButtonText: 'Ya, Ubah',
                            }).then(function (result) {
                                if (result.value) {
                                    $.ajax({
                                        url: "{{url('coach/schedule/update')}}/"+info.event.id,
                                        type: 'POST',
                                        data: {
                                            date: moment(info.event.start).format('DD MMMM YYYY'),
                                            time: moment(info.event.start).format('HH:mm:ss')
                                        },
                                    })
                                    .done(function(res, xhr, meta) {
                                        renderCalender()
                                    });
                                }else{
                                    info.revert();
                                }
                            })

                            $('.swal2-title').addClass('justify-content-center')
                        }else{
                            info.revert();
                        }
                    },
                    eventClick: function(info) {
                        calendarDetail(info.event.id);
                    }
                });

                renderCalender()

                calendar.render();
            },
            renderCalender = () => {
                if(calendar){
                    calendar.removeAllEvents();
                }

                $.ajax({
                    url: "{{url('coach/schedule/all')}}",
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {

                    $.each(res.data, function(index, data){
                        calendar.addEvent({
                            "id": data.id,
                            "title": data.name,
                            "start": data.datetime,
                            "className": `bg-${data.color} border-${data.color} p-1 ${data.color == 'primary' || data.color == 'info' ? 'text-white' : ''}`,
                            "source_type": data.type
                        });
                    })

                });
            },
            calendarDetail = (id) => {
                $.ajax({
                    url: "{{url('coach/schedule')}}/"+id,
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    $('.coach-image').attr('src', res.data.image_url)
                    $('.coach-name').text(res.data.coach_name)
                    $('.class-name').text(res.data.name)
                    $('.package-name').text(res.data.package_type)
                    $('.session-place').text(res.data.session ? res.data.session+'/'+res.data.session_total : '-')
                    $('.student-name').text(res.data.student_name ? res.data.student_name : '-')
                    $('.date-place').text(moment(res.data.datetime).format('dddd, DD MMMM YYYY'))
                    $('.time-place').text(moment(res.data.datetime).format('HH:mm')+' - '+moment(res.data.end_datetime).format('HH:mm'))

                    $('.function-btn').attr('data-id', res.data.id);

                    if(res.data.status == 1){
                        $('.btn-edit').hide()
                        $('.btn-delete').hide()
                    }else if(res.data.status == 2){
                        $('.btn-edit').show()
                        $('.btn-delete').show()
                    }else if(res.data.status == 3){
                        $('.btn-edit').hide()
                        $('.btn-delete').hide()
                    }else if(res.data.status == 4){
                        $('.btn-delete').show()
                    }

                    showModal('modal-schedule-detail');
                });
            },
            initAction = () => {
                $(document).on('change', '#classroom', function(){
                    if($(this).val() != ''){
                        classroom_detail($(this).val())
                    }else{
                        classroom_detail();
                    }
                })

                $(document).on('click', '.btn-edit', function(){
                    event.preventDefault();

                    let id = $(this).attr('data-id');

                    $.ajax({
                        url: "{{url('coach/schedule-show')}}/"+id,
                        type: 'GET',
                        dataType: 'json',
                    })
                    .done(function(res, xhr, meta) {

                        $('#form-schedule').trigger("reset");
                        $('#form-schedule').attr('action', "{{url('coach/schedule')}}/"+id);
                        $('#form-schedule').attr('method','POST');

                        $('#form-schedule').find('input[name="date"]').val(moment(res.data.datetime).format('DD MMMM YYYY'));
                        $('#form-schedule').find('input[name="time"]').val(moment(res.data.datetime).format('HH:mm:ss'));
                        $('#form-schedule').find('input[name="platform_link"]').val(res.data.platform_link);
                        $("input[name=type_class][value=" + 1 + "]").attr('checked', 'checked');

                        get_classroom_edit(res.data.classroom_category_id, res.data.sub_classroom_category_id, res.data.classroom_id)
                        get_platform(res.data.platform_id)

                        showModal('modal-schedule')
                        hideModal('modal-schedule-detail');
                    });
                })

                $(document).on('click', '.btn-delete', function(){
                    let id = $(this).attr('data-id');

                    Swal.fire({
                        title: 'Hapus Jadwal?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7F16A7',
                        confirmButtonText: 'Ya, Hapus',
                    }).then(function (result) {
                        if (result.value) {
                            $.ajax({
                                url: "{{url('coach/schedule/delete')}}/"+id,
                                type: 'POST',
                            })
                            .done(function(res, xhr, meta) {
                                renderCalender()
                                hideModal('modal-schedule-detail');
                            });
                        }
                    })

                    $('.swal2-title').addClass('justify-content-center')
                })
            },
            classroom_detail = (id) => {
                $.ajax({
                    url: "{{url('public/get-classroom-detail')}}/"+id,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    if(res.data){
                        $('#duration').val(res.data.session_duration + ' Menit')
                    }else{
                        $('#duration').val('-')
                    }
                });
            },
            formSubmit = () => {
                $('#form-schedule').submit(function(event){
                    event.preventDefault();

                    let form_data = new FormData(this)

                    if(arr_path.length > 0){
                        form_data.append('file', arr_path[0]['path']);
                    }

                    btn_loading('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            toastr.success(res.message, 'Success')
                            arr_path = [];
                            hideModal('modal-schedule');
                            renderCalender()
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
            }

            const get_classroom_coach = () => {
                if(init_classroom){
                    init_classroom.destroy();
                }

                init_classroom = new SlimSelect({
                    select: '#classroom'
                })

                $.ajax({
                    url: '{{url('public/get-classroom-coach')}}',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Select Class</option>`

                    $.each(res.data, function(index, data) {
                        element += `<option value="${data.id}">${data.name}</option>`;
                    });

                    $('#classroom').html(element);
                })
            },
            get_classroom_edit = (category_id, sub_category_id, select_id) => {
                if(init_classroom){
                    init_classroom.destroy();
                }

                init_classroom = new SlimSelect({
                    select: '#classroom'
                })

                if(category_id == ''){
                    category_id = undefined;
                }

                if(sub_category_id == ''){
                    category_id = undefined;
                }

                $.ajax({
                    url: '{{url('public/get-classroom')}}/'+category_id+'&'+sub_category_id,
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Select Class</option>`

                    $.each(res.data, function(index, data) {
                        if(select_id == data.id){
                            element += `<option value="${data.id}" selected>${data.name}</option>`;
                        }else{
                            element += `<option value="${data.id}">${data.name}</option>`;
                        }
                    });

                    $('#classroom').html(element);
                })
            },
            get_platform = (select_id) => {
                if(init_platform){
                    init_platform.destroy();
                }

                init_platform = new SlimSelect({
                    select: '#platform'
                })

                $.ajax({
                    url: '{{url('public/get-platform')}}',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Select Media</option>`

                    $.each(res.data, function(index, data) {
                        if(select_id == data.id){
                            element += `<option value="${data.id}" selected>${data.name}</option>`;
                        }else{
                            element += `<option value="${data.id}">${data.name}</option>`;
                        }
                    });

                    $('#platform').html(element);
                })
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
