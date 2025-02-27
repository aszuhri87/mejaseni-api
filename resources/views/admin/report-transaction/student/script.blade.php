<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, init_select_student_id;

            $(document).ready(function() {
                // formSubmit();
                // initTable();
                initAction();
                initDatePicker();
                getStudent();

                init_select_student_id = new SlimSelect({
                    select: '#student_id'
                });
            });

            const initTable = (student_id,date_start,date_end) => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/report/transaction-report/student/dt') }}",
                        data: {
                            student_id: student_id,
                            date_start: date_start,
                            date_end: date_end,
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'number' },
                        { data: 'datetime' },
                        { data: 'id' },
                        { data: 'price' },
                        { data: 'status' }
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
                            searchable: true,
                            orderable: true,
                            className: "text-left",
                            data: "datetime",
                            render: function(data, type, full, meta){
                                return `<strong>${moment(data).format('DD MMMM YYYY')}</strong><br><span class="text-muted">${moment(data).format('HH:mm')}</span>`;
                            }
                        },
                        {
                            targets: 3,
                            searchable: true,
                            orderable: true,
                            className: "text-left",
                            data: "id",
                            render: function(data, type, full, meta){
                                if(full.classroom_name){
                                    let element = `<strong>${full.classroom_name}</strong>`;
                                    if(full.package_type == 1){
                                        element +=`<br><span class="text-muted">Special Class</span>`;
                                    }
                                    else{
                                        element +=`<br><span class="text-muted">Regular Class</span>`;
                                    }
                                    return element;
                                }
                                else if(full.master_lessons_name){
                                    let element = `
                                        <strong>${full.master_lessons_name}</strong>
                                        <br><span class="text-muted">Master Lesson Class</span>
                                    `;
                                    return element;
                                }
                                else if(full.session_video_name){
                                    let element = `
                                        <strong>${full.session_video_name}</strong>
                                        <br><span class="text-muted">Video Class</span>
                                    `;
                                    return element;
                                }
                                else{
                                    let element = `
                                        <strong>${full.theory_name}</strong>
                                        <br><span class="text-muted">Theory Class</span>
                                    `;
                                    return element;
                                }
                            }
                        },
                        {
                            targets: 4,
                            searchable: true,
                            orderable: true,
                            className: "text-left",
                            data: "price",
                            render: function(data, type, full, meta){
                                return `<strong>Rp. ${numeral(data).format('0,0')}</strong>`;
                            }
                        },
                        {
                            targets: -1,
                            searchable: true,
                            orderable: true,
                            className: "text-left",
                            data: "status",
                            render: function(data, type, full, meta){
                                if(data == 0){
                                    return `<span class="label label-pill label-inline label-danger mr-2">Cancel<span>`
                                }
                                else if(data == 1){
                                    return `<span class="label label-pill label-inline label-secondary mr-2">Waiting<span>`
                                }
                                else if(data == 2){
                                    return `<span class="label label-pill label-inline label-success mr-2">Success<span>`
                                }else{
                                    return `<span class="label label-pill label-inline label-warning mr-2">Return<span>`
                                }
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

                $('#search').keyup(searchDelay(function(event) {
                    init_table.search($(this).val()).draw()
                }, 1000));

                $('#pageLength').on('change', function () {
                    init_table.page.len(this.value).draw();
                });
            },
            initDatePicker = () => {
                if (KTUtil.isRTL()) {
                    arrows = {
                        leftArrow: '<i class="la la-angle-right"></i>',
                        rightArrow: '<i class="la la-angle-left"></i>'
                    }
                } else {
                    arrows = {
                        leftArrow: '<i class="la la-angle-left"></i>',
                        rightArrow: '<i class="la la-angle-right"></i>'
                    }
                }
                $('.datepicker').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: arrows,
                    format:'d MM yyyy'
                });
            },
            initAction = () => {
                $(document).on('click','#btn-filter',function(event){
                    event.preventDefault();
                    let student_id = $('#student_id').val();
                    $('#date-start').val('');
                    $('#date-end').val('');
                    $('#card-table').show();
                    initTable(student_id, null, null);
                });

                $(document).on('click','.btn-see',function(event){
                    event.preventDefault();
                    var data = init_table.row($(this).parents('tr')).data();
                    let date = moment(data.datetime).format('DD MMMM YYYY');
                    let time = moment(data.datetime).format('HH:mm');

                    $('#date').html(date);
                    $('#time').html(time);
                    $('#name').html(data.student_name);
                    $('#description').html(data.description);

                    $('#input-id').rating('update', data.star);
                    showModal('modal-class-detail');
                });

                $(document).on('change','#date-start',function() {
                    if($(this).val() != null || $(this).val() != ''){
                        if($('#date-end').val() != null || $('#date-end').val() != ''){
                            let date_start = $(this).val();
                            let date_end = $('#date-end').val();
                            initTable(null,date_start,date_end);
                        }
                    }
                });

                $(document).on('change','#date-end',function() {
                    if($(this).val() != null || $(this).val() != ''){
                        if($('#date-start').val() != null || $('#date-start').val() != ''){
                            let date_start = $('#date-start').val();
                            let date_end = $(this).val();
                            if(moment(moment(date_start).format('YYYY-MM-DD')).isSameOrBefore(moment(date_end).format('YYYY-MM-DD'))){
                                initTable(null,date_start,date_end);
                            }
                            else{
                                toastr.error('Tanngal mulai harus lebih kecil','Failed')
                            }
                        }
                    }
                });

                $(document).on('click','.btn-excel',function(event){
                    event.preventDefault();
                    $('#form-submit').attr('action',$(this).attr('href'));
                    $('#form-submit').submit();
                });

                $(document).on('click','.btn-pdf',function(event){
                    event.preventDefault();
                    $('#form-submit').attr('action',$(this).attr('href'));
                    $('#form-submit').submit();
                });
            },
            formSubmit = () => {
                $('#form-classroom-category').submit(function(event){
                    event.preventDefault();

                    btn_loading('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        toastr.success(res.message, 'Success')
                        init_table.draw(false);
                        hideModal('modal-classroom-category');
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
            },
            getStudent = () => {
                $.ajax({
                    url: `{{ url('public/get-student') }}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Pilih Siswa</option>`;
                    $.each(res.data, function(index, data) {
                        element += `<option value="${data.id}">${data.name} - ${data.email}</option>`
                    });

                    $('#student_id').html(element);

                    if(init_select_student_id){
                        init_select_student_id.destroy();
                    }

                    init_select_student_id = new SlimSelect({
                        select: '#student_id'
                    });

                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                    btn_loading('stop')
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
