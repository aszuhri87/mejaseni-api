<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table;

            var calendar,
                arr_path = [],
                docsDropzone,
                init_classroom_category,
                init_sub_classroom_category,
                init_classroom,
                init_platform,
                init_coach;

            $(document).ready(function() {
                initTable();
                initAction();
                formSubmit();
                get_platform();
                get_coach();
                get_classroom();
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/schedule-list/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'datetime' },
                        { data: 'name' },
                        { data: 'coach' },
                        { data: 'status_text' },
                        { defaultContent: '' },
                        ],
                    columnDefs: [
                        {
                            targets: 0,
                            searchable: false,
                            orderable: false,
                            className: "text-center"
                        },
                        {
                            targets: 1,
                            data:"datetime",
                            render: function(data, type, full, meta){
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">${moment(data).format('DD MMMM YYYY')}</p>
                                        <span class="text-muted">${moment(data).format('HH:MM:ss')}</span>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: 4,
                            data:"status_text",
                            render: function(data, type, full, meta){
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg text-${full.color}">${full.status_text}</p>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: -1,
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                            data: "id",
                            render : function(data, type, full, meta) {
                                return `
                                    <a href="javascript:void(0);" title="Edit" class="btn btn-view btn-sm btn-clean btn-icon mr-2">
                                        <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Visible.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </a>
                                    `
                            }
                        },
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

                $('#search').keyup(searchDelay(function(event) {
                    init_table.search($(this).val()).draw()
                }, 1000));

                $('#pageLength').on('change', function () {
                    init_table.page.len(this.value).draw();
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
                                toastr.success(res.message, 'Success')
                                init_table.draw(false)
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
                                toastr.success(res.message, 'Success')
                                init_table.draw(false)
                                hideModal('modal-schedule-detail');
                            });
                        }
                    })

                    $('.swal2-title').addClass('justify-content-center')
                })

                $(document).on('click','.btn-view',function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();
                    calendarDetail(data.id)

                })
            },
            formSubmit = () => {
                $('#form-schedule').submit(function(event){
                    event.preventDefault();

                    let form_data = new FormData(this)

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
                        init_table.draw(false)
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
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
