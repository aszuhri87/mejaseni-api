<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table;

            $(document).ready(function() {
                initTable();
                formSubmit();
                initAction();
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('student/my-class/booking/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'datetime' },
                        { data: 'datetime' },
                        { data: 'classroom_name' },
                        { data: 'coach_name' },
                        { data: 'datetime' },
                        { defaultContent: '' }
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
                            searchable: true,
                            orderable: true,
                            className: "text-left",
                            data:"datetime",
                            render:function(data, type, full, meta){
                                return `${moment(data).format('DD MMMM YYYY')}`;
                            }
                        },
                        {
                            targets: 2,
                            searchable: true,
                            orderable: true,
                            className: "text-center",
                            data:"datetime",
                            render:function(data, type, full, meta){
                                return `${moment(data).format('hh:mm')}`;
                            }
                        },
                        {
                            targets: -2,
                            searchable: true,
                            orderable: true,
                            className: "text-left",
                            data:"datetime",
                            render:function(data, type, full, meta){
                                if(moment(data).isSameOrBefore(moment())){
                                    return `<span class="text-primary">On Going</span>`;
                                }
                                else{
                                    var now = moment().format('YYYY-MM-DD H:mm:ss');
                                    var date = moment(data).format('YYYY-MM-DD H:mm:ss');
                                    let minute, hour, day, time;

                                    minute = moment(date).diff(now, 'minutes')
                                    hour = moment(date).diff(now, 'hours')
                                    day = moment(date).diff(now, 'days')

                                    if(day > 0){
                                        time = 'In '+ day + ' Day ';
                                    }else if(hour > 0){
                                        time = 'In '+ hour + ' Hours ';
                                    }else{
                                        time = 'In '+ minute + ' Minutes';
                                    }

                                    return `${time}`;
                                }
                            }
                        },
                        {
                            targets: -1,
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                            data: "id",
                            render : function(data, type, full, meta) {
                                if(moment(full.datetime).isSameOrBefore(moment())){
                                    return `<span class="text-primary">On Going</span>`;
                                }
                                else{
                                    return `
                                        <a href="{{url('student/my-class/reschedule/')}}/${data}" title="Lihat Detail" class="btn btn-detail btn-sm btn-outline-primary mr-2">
                                            <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Visible.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                    <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                                </g>
                                            </svg><!--end::Svg Icon--></span>
                                            Lihat Detail
                                        </a>
                                        `;
                                }
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
            initAction = () => {
                $(document).on('click', '#add-btn', function(event){
                    event.preventDefault();

                    $('#form-invoice').trigger("reset");
                    $('#form-invoice').attr('action','{{url('admin/master/courses/classroom-category')}}');
                    $('#form-invoice').attr('method','POST');

                    showModal('modal-invoice');
                });

                $(document).on('click', '.btn-detail', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-reschedule').trigger("reset");
                    $('#form-reschedule').attr('action', $(this).attr('href'));
                    $('#form-reschedule').attr('method','PUT');

                    $('#classroom-name').html(data.classroom_name);
                    $('#date').html(moment(data.datetime).format('DD MMMM YYYY'));
                    $('#time').html(moment(data.datetime).format('hh:mm'));
                    $('#coach-name').html(data.coach_name);
                    var now = moment().format('YYYY-MM-DD H:mm:ss');
                    var date = moment(data.datetime).format('YYYY-MM-DD H:mm:ss');
                    let day;

                    day = moment(date).diff(now, 'days')
                    if(day > 0){
                        $('#reschedule').show();
                    }else{
                        $('#reschedule').hide();
                    }

                    showModal('modal-reschedule');
                });

                $(document).on('click', '.btn-delete', function(event){
                    event.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Delete Classroom Category?',
                        text: "Deleted Classroom Category will be permanently lost!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7F16A7',
                        confirmButtonText: 'Yes, Delete',
                    }).then(function (result) {
                        if (result.value) {
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                dataType: 'json',
                            })
                            .done(function(res, xhr, meta) {
                                toastr.success(res.message, 'Success')
                                init_table.draw(false);
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() { });
                        }
                    })
                    $('.swal2-title').addClass('justify-content-center')
                });
            },
            formSubmit = () => {
                $('#form-reschedule').submit(function(event){
                    event.preventDefault();

                    btn_loading_basic('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            toastr.success(res.message, 'Success')
                            init_table.draw(false);
                            hideModal('modal-reschedule');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_basic('stop');
                    });
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
