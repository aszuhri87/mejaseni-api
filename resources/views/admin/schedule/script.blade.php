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
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,list'
                    },
                    locale: 'ind',
                    timeZone: 'local',
                    initialView: 'dayGridMonth',
                    eventColor: '#fff',
                    editable: true,
                    selectable: true,
                    select: function(info) {
                        $('#form-schedule').trigger("reset");

                        $('.timepicker').val(moment(info.start).format('HH:mm:ss') == '00:00:00' ? moment().format('HH:mm:ss') : moment(info.start).format('HH:mm:ss'))
                        $('.datepicker').val(moment(info.start).format('D MMMM YYYY'))

                        get_classroom_category();
                        get_sub_classroom_category();

                        $('#form-schedule').attr('action','{{url('admin/schedule')}}');
                        $('#form-schedule').attr('method','POST');

                        showModal('modal-schedule');
                    }
                });

                calendar.render();
            }
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
                        init_table.draw(false);
                        arr_path = [];
                        hideModal('modal-session-video-detail');
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
                        get_classroom()
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
