<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table,init_table2,init_input_star;

            $(document).ready(function() {
                initTable();
                initTable2();
                formSubmit();
                initAction();
                studentRating();
                totalClassStudent();
                getClass();

                init_input_star = $('#input-id').rating({
                    hoverOnClear: false,
                    theme: 'krajee-svg',
                    showCaption: false,
                    showClear:false,
                    showCaptionAsTitle:false,
                    step:1,
                });

                $('#input-rating-class').rating({
                    hoverOnClear: false,
                    theme: 'krajee-svg',
                    showCaption: false,
                    showClear:false,
                    showCaptionAsTitle:false,
                    step:1
                });
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
                                return `${moment(data).format('HH:mm')}`;
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
                                    var now = moment().format('YYYY-MM-DD HH:mm:ss');
                                    var date = moment(data).format('YYYY-MM-DD HH:mm:ss');
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
                                if(moment(full.datetime).isSameOrBefore(moment().format('YYYY-MM-DD HH:mm:ss'))){
                                    return `
                                        <a href="{{url('student/my-class/checkin/')}}/${data}" title="Masuk Kelas" class="btn btn-enter-class btn-sm btn-primary mr-2">
                                            <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Navigation/Arrow-from-left.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                    <rect fill="#000000" opacity="0.3" transform="translate(14.000000, 12.000000) rotate(-90.000000) translate(-14.000000, -12.000000) " x="13" y="5" width="2" height="14" rx="1"/>
                                                    <rect fill="#000000" opacity="0.3" x="3" y="3" width="2" height="18" rx="1"/>
                                                    <path d="M11.7071032,15.7071045 C11.3165789,16.0976288 10.6834139,16.0976288 10.2928896,15.7071045 C9.90236532,15.3165802 9.90236532,14.6834152 10.2928896,14.2928909 L16.2928896,8.29289093 C16.6714686,7.914312 17.281055,7.90106637 17.675721,8.26284357 L23.675721,13.7628436 C24.08284,14.136036 24.1103429,14.7686034 23.7371505,15.1757223 C23.3639581,15.5828413 22.7313908,15.6103443 22.3242718,15.2371519 L17.0300721,10.3841355 L11.7071032,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(16.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-16.999999, -11.999997) "/>
                                                </g>
                                            </svg><!--end::Svg Icon--></span>
                                            Masuk
                                        </a>
                                    `;
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
            initTable2 = () => {
                init_table2 = $('#init-table2').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('student/my-class/last-class/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'datetime' },
                        { data: 'datetime' },
                        { data: 'classroom_name' },
                        { data: 'coach_name' },
                        { data: 'status_checkin' },
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
                            data:"status_checkin",
                            render:function(data, type, full, meta){
                                if(data){
                                    return `<span class="label label-success label-pill label-inline mr-2">Hadir</span>`
                                }else{
                                    return `<span class="label label-danger label-pill label-inline mr-2">Tidak Hadir</span>`
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
                                if(full.status_checkin && !full.is_review_session){
                                    return `
                                        <a href="{{url('student/my-class/review')}}/${data}" title="Berikan Review" class="btn btn-review btn-sm btn-primary mr-2">
                                            Berikan Review
                                            <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Navigation/Right-2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                    <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                                    <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                                </g>
                                            </svg><!--end::Svg Icon--></span>
                                        </a>
                                        `;
                                }
                                else if(full.status_checkin && full.is_review_session){
                                    return `
                                        <a href="{{url('student/my-class/get-review')}}/${full.id}" title="Lihat Review" class="btn btn-see-review btn-sm btn-outline-primary mr-2">
                                            <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Visible.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                    <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                                </g>
                                            </svg><!--end::Svg Icon--></span>
                                            Lihat Review
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

                $('#search2').keyup(searchDelay(function(event) {
                    init_table2.search($(this).val()).draw()
                }, 1000));

                $('#pageLength2').on('change', function () {
                    init_table2.page.len(this.value).draw();
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
                    $('#time').html(moment(data.datetime).format('HH:mm'));
                    $('#coach-name').html(data.coach_name);
                    var now = moment().format('YYYY-MM-DD H:mm:ss');
                    var date = moment(data.datetime).format('YYYY-MM-DD HH:mm:ss');
                    let day;

                    day = moment(date).diff(now, 'days')
                    if(day > 0){
                        $('#reschedule').show();
                    }else{
                        $('#reschedule').hide();
                    }

                    showModal('modal-reschedule');
                });

                $(document).on('click','.btn-review',function(event){
                    event.preventDefault();
                    var data = init_table2.row($(this).parents('tr')).data();

                    $('#form-review').trigger('reset');
                    $('#form-review').attr('action',$(this).attr('href'));
                    $('#form-review').attr('method','POST');

                    $('#review-classroom-name').html(data.classroom_name);
                    $('#review-date').html(moment(data.datetime).format('DD MMMM YYYY'));
                    $('#review-coach-name').html(data.coach_name);
                    $('#review-time').html(moment(data.datetime).format('HH:mm'));

                    if(init_input_star){
                        init_input_star.rating('destroy');
                    }
                    init_input_star = $('#input-id').rating({
                        hoverOnClear: false,
                        theme: 'krajee-svg',
                        showCaption: false,
                        showClear:false,
                        showCaptionAsTitle:false,
                        step:1,
                    });

                    $('#btn-send-review').show();

                    showModal('modal-review');
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

                $(document).on('click','.btn-rating-class',function(event){
                    event.preventDefault();

                    $('#form-rating-class').attr('action',`{{url('student/my-class/rating')}}`);
                    $('#form-rating-class').attr('method',`POST`);
                    $('#form-rating-class').find('input[name="rating_class"]').trigger('reset');
                    $('#form-rating-class').find('textarea[name="commentar"]').trigger('reset');

                    showModal('modal-rating-class');
                });

                $(document).on('click','.btn-enter-class',function(event){
                    event.preventDefault();
                    KTApp.blockPage({
                        overlayColor: '#000000',
                        state: 'primary',
                        opacity: 0.5,
                        message: 'Processing...'
                    });
                    $.ajax({
                        url: $(this).attr('href'),
                        type: 'GET',
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            window.open(res.data.platform_link);
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        KTApp.unblockPage();
                    });
                });

                $(document).on('click','.btn-see-review',function(event){
                    event.preventDefault();
                    $.ajax({
                        url: $(this).attr('href'),
                        type: 'GET',
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            $('#btn-send-review').hide();
                            $('#review-classroom-name').html(res.data.classroom_name);
                            $('#review-date').html(moment(res.data.datetime).format('DD MMMM YYYY'));
                            $('#review-coach-name').html(res.data.coach_name);
                            $('#review-time').html(moment(res.data.datetime).format('HH:mm'));
                            if(init_input_star){
                                init_input_star.rating('destroy');
                            }
                            init_input_star = $('#input-id').rating({
                                hoverOnClear: false,
                                theme: 'krajee-svg',
                                showCaption: false,
                                showClear:false,
                                showCaptionAsTitle:false,
                                step:1,
                                disabled:true,
                                readonly:true,
                            });
                            $('#input-id').rating('update', res.data.star);
                            $('#commentar').val(res.data.description);

                            showModal('modal-review');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {

                    });
                })
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
                $('#form-review').submit(function(event){
                    event.preventDefault();

                    btn_loading_rating('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize()
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            toastr.success(res.message, 'Success')
                            init_table2.draw(false);
                            hideModal('modal-review');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_rating('stop');
                    });
                });

                $('#form-rating-class').submit(function(event){
                    event.preventDefault();

                    btn_loading_rating('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize()
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            console.log(res.data);
                            return;
                            hideModal('modal-rating-class');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_rating('stop');
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
                                    if(data.subtraction == 0){
                                        if(data.is_rating){
                                            $('#rating-class').hide();
                                        }
                                        else{
                                            $('#rating-class').show();
                                        }
                                    }
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
