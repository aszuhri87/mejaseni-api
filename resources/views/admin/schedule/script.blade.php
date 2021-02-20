<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var calendar,
                arr_path = [],
                docsDropzone,
                init_classroom_category,
                init_sub_classroom_category,
                init_classroom,
                init_platform,
                init_coach;

            $(document).ready(function() {
                initCalendar();
                initAction();
                formSubmit();
                get_platform();
                get_coach();
                get_classroom();
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
                    events: renderCalender(),
                    height: 750,
                    select: function(info) {
                        $('#form-schedule').trigger("reset");

                        $('.timepicker').val(moment(info.start).format('HH:mm:ss') == '00:00:00' ? moment().format('HH:mm:ss') : moment(info.start).format('HH:mm:ss'))
                        $('.datepicker').val(moment(info.start).format('D MMMM YYYY'))

                        get_classroom_category();
                        get_sub_classroom_category();

                        $('#form-schedule').attr('action','{{url('admin/schedule')}}');
                        $('#form-schedule').attr('method','POST');

                        showModal('modal-schedule');
                    },
                    eventDrop: function(info) {
                        Swal.fire({
                            title: 'Ubah Jadwal?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#7F16A7',
                            confirmButtonText: 'Ya, Ubah',
                        }).then(function (result) {
                            if (result.value) {
                                $.ajax({
                                    url: "{{url('admin/schedule/update')}}/"+info.event.id,
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
                    },
                    eventClick: function(info) {
                        calendarDetail(info.event.id);
                    }
                });

                calendar.render();
            },
            renderCalender = () => {
                if(calendar){
                    calendar.removeAllEvents();
                }

                $.ajax({
                    url: "{{url('admin/schedule/all')}}",
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {

                    let schedules = [];

                    $.each(res.data, function(index, data){
                        calendar.addEvent({
                            "id": data.id,
                            "title": data.name,
                            "start": data.datetime,
                            "className": `bg-${data.color} border-${data.color} p-1 ${data.color == 'primary' ? 'text-white' : ''}`
                        });
                    })

                    return schedules;

                });
            },
            calendarDetail = (id) => {
                $.ajax({
                    url: "{{url('admin/schedule')}}/"+id,
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
                        $('.btn-confirm').hide()
                    }else if(res.data.status == 2){
                        $('.btn-edit').show()
                        $('.btn-delete').show()
                        $('.btn-confirm').show()
                    }else if(res.data.status == 3){
                        $('.btn-edit').hide()
                        $('.btn-delete').hide()
                        $('.btn-confirm').hide()
                    }else if(res.data.status == 4){
                        $('.btn-edit').show()
                        $('.btn-delete').show()
                        $('.btn-confirm').hide()
                    }

                    showModal('modal-schedule-detail');
                });
            },
            initAction = () => {
                $(document).on('change', '.type-class', function(){
                    if($(this).val() == 1){
                        $('.form-package').show();
                        $('.form-master-lesson').hide();
                    }else{
                        $('.form-package').hide();
                        $('.form-master-lesson').show();
                    }
                })

                $(document).on('change', '#classroom-category', function(){
                    if($('#classroom-category').val() == ""){
                        $('.required-classroom-category').show();
                        $('.select-sub-category').hide();
                        $('.parent-sub-category').removeClass('col-md-6');
                        get_classroom()
                    }else{
                        $('.required-classroom-category').hide();
                        get_sub_classroom_category($('#classroom-category').val())
                    }
                })

                $(document).on('change', '#sub-classroom-category', function(){
                    get_classroom($('#classroom-category').val(), $('#sub-classroom-category').val())
                })

                $(document).on('change', '#classroom', function(){
                    get_coach($('#classroom').val())
                })

                $(document).on('click', '.btn-edit', function(){
                    event.preventDefault();

                    let id = $(this).attr('data-id');

                    $.ajax({
                        url: "{{url('admin/schedule-show')}}/"+id,
                        type: 'GET',
                        dataType: 'json',
                    })
                    .done(function(res, xhr, meta) {

                        $('#form-schedule').trigger("reset");
                        $('#form-schedule').attr('action', "{{url('admin/schedule')}}/"+id);
                        $('#form-schedule').attr('method','POST');

                        $('#form-schedule').find('input[name="date"]').val(moment(res.data.datetime).format('DD MMMM YYYY'));
                        $('#form-schedule').find('input[name="time"]').val(moment(res.data.datetime).format('HH:mm:ss'));
                        $('#form-schedule').find('input[name="platform_link"]').val(res.data.platform_link);
                        $("input[name=type_class][value=" + 1 + "]").attr('checked', 'checked');

                        get_classroom_category(res.data.classroom_category_id)

                        if(res.data.sub_classroom_category_id){
                            get_sub_classroom_category(res.data.classroom_category_id, res.data.sub_classroom_category_id)
                            $('.parent-sub-category').removeClass('col-12')
                        }else{
                            $('.parent-sub-category').addClass('col-12')
                            $('.select-sub-category').hide();
                        }

                        get_classroom_edit(res.data.classroom_category_id, res.data.sub_classroom_category_id, res.data.classroom_id)
                        get_coach(res.data.classroom_id, res.data.coach_id)
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
                                url: "{{url('admin/schedule/delete')}}/"+id,
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

                $(document).on('click', '.btn-confirm', function(){
                    let id = $(this).attr('data-id');

                    Swal.fire({
                        title: 'Konfirmasi Jadwal?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7F16A7',
                        confirmButtonText: 'Ya, Konfirmasi',
                    }).then(function (result) {
                        if (result.value) {
                            $.ajax({
                                url: "{{url('admin/schedule/confirm')}}/"+id,
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
                        toastr.success(res.message, 'Success')
                        arr_path = [];
                        hideModal('modal-schedule');
                        renderCalender()
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
            }

            const initDropzone = () => {
                docsDropzone = new Dropzone( "#kt_dropzone_2", {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{url('admin/master/theory/file')}}",
                    paramName: "file",
                    maxFiles: 1,
                    maxFilesize: 2,
                    uploadMultiple: false,
                    addRemoveLinks: true,
                    init: function() {
                        this.on("maxfilesexceeded", function(file) {
                                this.removeAllFiles();
                                this.addFile(file);
                        });
                    },
                    success: function (file, response) {
                        arr_path.push({id: file.upload.uuid, path_id: response.data.id, path: response.data.path})
                        $('.dz-remove').addClass('dz-style-custom');
                    },
                    removedfile: function (file) {
                        $.each(arr_path, function(index, arr_data){
                            if(file.upload.uuid == arr_data['id']){
                                arr_path.splice(index, 1);

                                $.ajax({
                                    url: "{{url('admin/master/theory/file')}}/"+arr_data['path_id'],
                                    type: 'DELETE',
                                    dataType: 'json',
                                })
                                .done(function(res, xhr, meta) {

                                })
                                .fail(function(res, error) {
                                    toastr.error(res.responseJSON.message, 'Failed')
                                })
                            }
                        })

                        var _ref;
                        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                    },
                });
            }

            const get_classroom_category = (id) => {
                if(init_classroom_category){
                    init_classroom_category.destroy();
                }

                init_classroom_category = new SlimSelect({
                    select: '#classroom-category'
                })

                $.ajax({
                    url: '{{url('public/get-classroom-category')}}',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Select Class Category</option>`
                    $.each(res.data, function(index, data) {
                        if(id == data.id){
                            element += `<option value="${data.id}" selected>${data.name}</option>`;
                        }else{
                            element += `<option value="${data.id}">${data.name}</option>`;
                        }
                    });

                    $('#classroom-category').html(element);
                })
            },
            get_sub_classroom_category = (id, select_id, on = true) => {
                if(init_sub_classroom_category){
                    init_sub_classroom_category.destroy();
                }

                init_sub_classroom_category = new SlimSelect({
                    select: '#sub-classroom-category'
                })

                $.ajax({
                    url: '{{url('public/get-sub-classroom-category-by-category')}}/'+id,
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    if(res.data.length > 0){
                        $('.select-sub-category').show();

                        let element = `<option value="">Select Sub Class Category</option>`

                        $.each(res.data, function(index, data) {
                            if(select_id == data.id){
                                element += `<option value="${data.id}" selected>${data.name}</option>`;
                            }else{
                                element += `<option value="${data.id}">${data.name}</option>`;
                            }
                        });

                        $('#sub-classroom-category').html(element);
                        $('.parent-sub-category').addClass('col-md-6');
                        if(!select_id && !id){
                            get_classroom()
                        }
                    }else{
                        $('.select-sub-category').hide();
                        $('.parent-sub-category').removeClass('col-md-6');

                        if(on){
                            get_classroom($('#classroom-category').val())
                        }
                    }
                })
            },
            get_classroom = (category_id, sub_category_id, select_id) => {
                if(init_classroom){
                    init_classroom.destroy();
                }

                init_classroom = new SlimSelect({
                    select: '#classroom'
                })

                if(category_id == ''){
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
                    get_coach();
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
            get_coach = (id, select_id) => {
                if(init_coach){
                    init_coach.destroy();
                }

                init_coach = new SlimSelect({
                    select: '#coach'
                })

                $.ajax({
                    url: '{{url('public/get-coach-by-class')}}/'+ id,
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Select Coach</option>`

                    $.each(res.data, function(index, data) {
                        if(select_id == data.id){
                            element += `<option value="${data.id}" selected>${data.name}</option>`;
                        }else{
                            element += `<option value="${data.id}">${data.name}</option>`;
                        }
                    });

                    $('#coach').html(element);
                })
            }
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
