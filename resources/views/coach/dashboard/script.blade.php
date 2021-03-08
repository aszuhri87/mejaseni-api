<script type="text/javascript">
    var Page = function() {
        var _componentPage = function() {
            var init_table;

            $(document).ready(function() {
                initAction();
                formSubmit();
                getBalance();
                summary_course_chart();
                side_summary_course();
                closestScheduleTable();
                latestComplateClassTable();
                lastClassTable();
                initDatePicker();
            });

            const summary_course_chart = () => {
                    $('#summary_course_chart').empty();
                    $.ajax({
                            url: `{{ url('coach/dashboard/summary-course-chart') }}`,
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            if (res.status == 200) {
                                let data = 0;
                                $.each(res.data.total_booking, function(index, value) {
                                    data = data + value;
                                });

                                var options = {
                                    series: [{
                                        name: 'Kelas Hadir',
                                        data: res.data.kelas_dihadiri
                                    }, {
                                        name: 'Kelas dibooking',
                                        data: res.data.kelas_booking
                                    }],
                                    chart: {
                                        type: 'bar',
                                        stacked: true,
                                        height: 350,
                                        toolbar: {
                                            show: false
                                        }
                                    },
                                    plotOptions: {
                                        bar: {
                                            horizontal: false,
                                            columnWidth: ['5%'],
                                            endingShape: 'rounded'
                                        },
                                    },
                                    legend: {
                                        show: false
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    stroke: {
                                        show: true,
                                        width: 2,
                                        colors: ['transparent']
                                    },
                                    xaxis: {
                                        categories: res.data.range_time,
                                        axisBorder: {
                                            show: false,
                                        },
                                        axisTicks: {
                                            show: false
                                        },
                                        labels: {
                                            style: {
                                                colors: KTApp.getSettings()['colors']['gray'][
                                                    'gray-500'
                                                ],
                                                fontSize: '12px',
                                                fontFamily: KTApp.getSettings()['font-family']
                                            }
                                        }
                                    },
                                    yaxis: {
                                        min: -30,
                                        max: 30,
                                        labels: {
                                            style: {
                                                colors: KTApp.getSettings()['colors']['gray'][
                                                    'gray-500'
                                                ],
                                                fontSize: '12px',
                                                fontFamily: KTApp.getSettings()['font-family']
                                            }
                                        }
                                    },
                                    fill: {
                                        opacity: 1
                                    },
                                    states: {
                                        normal: {
                                            filter: {
                                                type: 'none',
                                                value: 0
                                            }
                                        },
                                        hover: {
                                            filter: {
                                                type: 'none',
                                                value: 0
                                            }
                                        },
                                        active: {
                                            allowMultipleDataPointsSelection: false,
                                            filter: {
                                                type: 'none',
                                                value: 0
                                            }
                                        }
                                    },
                                    tooltip: {
                                        style: {
                                            fontSize: '12px',
                                            fontFamily: KTApp.getSettings()['font-family']
                                        },
                                        y: {
                                            formatter: function(val) {
                                                return val + " student"
                                            }
                                        }
                                    },
                                    colors: ['#7F16A7', '#FFA800'],
                                    grid: {
                                        borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
                                        strokeDashArray: 4,
                                        yaxis: {
                                            lines: {
                                                show: true
                                            }
                                        }
                                    }
                                }

                                var chart = new ApexCharts(document.querySelector("#summary_course_chart"),
                                    options);

                                chart.render();
                            }
                        })
                        .fail(function(res, error) {
                            toastr.error(res.responseJSON.message, 'Failed')
                        })
                        .always(function() {

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
                        format:'d MM yyyy',
                        autoClose: true
                    });
                },
                side_summary_course = () => {
                    $.ajax({
                            url: `{{ url('coach/dashboard/side-summary-course') }}`,
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            if (res.status == 200) {
                                $('.total-kelas').text(res.data.total_kelas)
                                $('.video-tutorial').text(res.data.total_video)
                                $('.booking-saat-ini').text(res.data.total_booking)
                                $('.riwayat-booking').text(res.data.total_riwayat_booking)
                                $('.tidak-hadir').text(res.data.total_tidak_hadir)
                            }
                        })
                        .fail(function(res, error) {
                            toastr.error(res.responseJSON.message, 'Failed')
                        })
                        .always(function() {

                        });
                },
                closestScheduleTable = () => {

                    $.ajax({
                            url: "{{ url('coach/dashboard/closest-schedule') }}",
                            type: 'POST',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            let element = '';
                            $.each(res.data, function(index, data) {
                                const start = moment(data.datetime);
                                const end = moment();

                                let status = ``;
                                if (moment(data.datetime) > moment() && moment(data.datetime).subtract(data.session_duration, "minutes") < moment()) {
                                    status +=  `<a href="${data.platform_link}" target="_blank" class="btn btn-light-primary btn-sm">
                                                        Join Class
                                                        <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Navigation/Right-2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                                <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                                                <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                                            </g>
                                                        </svg><!--end::Svg Icon--></span>
                                                    </a>
                                                    <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        <i class="ki ki-bold-more-hor"></i>
                                                    </a>
                                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                        <!--begin::Navigation-->
                                                        <ul class="navi navi-hover">
                                                            <li class="navi-item">
                                                                <a href="{{ url('/coach/dashboard/cancle/schedule') }}/${data.id}" class="navi-link text-danger cancle-schedule-btn">
                                                                    Cancel Kelas
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!--end::Navigation-->
                                                    </div>`;
                                } else if ( moment() > moment(data.datetime).subtract(data.session_duration, "minutes")) {
                                    status +=  `<span class="text-primary font-weight-bolder">${end.to(start)}</span>
                                                    <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        <i class="ki ki-bold-more-hor"></i>
                                                    </a>
                                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                        <!--begin::Navigation-->
                                                        <ul class="navi navi-hover">
                                                            <li class="navi-item">
                                                                <a href="{{ url('/coach/dashboard/cancle/schedule') }}/${data.id}" class="navi-link text-danger cancle-schedule-btn">
                                                                    Cancel Kelas
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!--end::Navigation-->
                                                    </div>`;
                                } else{
                                    status +=  `<span class="text-danger font-weight-bolder">Cancel</span>`;
                                }

                                element += `<!--begin::Item-->
                                                <div class="d-flex align-items-center mb-10">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40 symbol-light-success mr-5">
                                                        <span class="symbol-label">
                                                            <img src="${data.image_url}" width="40" height="40" class="align-self-center rounded" alt=""/>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->
                                                    <!--begin::Text-->
                                                    <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                                        <a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">${data.student_name}</a>
                                                        <span class="text-muted">${data.class_name}</span>
                                                    </div>
                                                    <!--end::Text-->
                                                    <!--begin::Dropdown-->
                                                    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions"
                                                        data-placement="left">
                                                        ` + status + `
                                                    </div>
                                                    <!--end::Dropdown-->
                                                </div>
                                                <!--end::Item-->
                                        `
                            })

                            $('.closest-schedule').html(element);

                        });
                },
                latestComplateClassTable = () => {

                    $.ajax({
                            url: "{{ url('coach/dashboard/closest-schedule') }}",
                            type: 'POST',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {

                            let element = '';
                            $.each(res.data, function(index, data) {

                                const start = moment(data.datetime);
                                const end = moment();

                                element += `<!--begin::Item-->
                                                <div class="d-flex align-items-center mb-10">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40 symbol-light-success mr-5">
                                                        <span class="symbol-label">
                                                            <img src="${data.image_url}" width="40" height="40" class="align-self-center rounded" alt=""/>
                                                        </span>
                                                    </div>
                                                    <!--end::Symbol-->
                                                    <!--begin::Text-->
                                                    <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                                        <a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">${data.student_name}</a>
                                                        <span class="text-muted">${data.class_name}</span>
                                                    </div>
                                                    <!--end::Text-->
                                                    <!--begin::Dropdown-->
                                                    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions"
                                                        data-placement="left">
                                                        <span class="text-primary">${end.to(start)}</span>

                                                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            <i class="ki ki-bold-more-hor"></i>
                                                        </a>
                                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                            <!--begin::Navigation-->
                                                            <ul class="navi navi-hover">
                                                                <li class="navi-item">
                                                                    <a href="#" class="navi-link text-danger">
                                                                        Cancel Kelas
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                            <!--end::Navigation-->
                                                        </div>
                                                    </div>
                                                    <!--end::Dropdown-->
                                                </div>
                                                <!--end::Item-->
                                        `
                            })

                            $('.latest-complete-class').html(element);
                        });
                },
                lastClassTable = (date_start,date_end) => {
                    init_table = $('#last-class-table').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window)
                            .height() - 450,
                        ajax: {
                            type: 'POST',
                            url: "{{ url('coach/dashboard/dt-last-class') }}",
                            data: {
                                date_start: date_start,
                                date_end: date_end
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex'
                            },
                            {
                                data: 'date_class'
                            },
                            {
                                data: 'class_name'
                            },
                            {
                                data: 'package_type'
                            },
                            {
                                data: 'student_name'
                            },
                            {
                                data: 'status'
                            },
                            {
                                defaultContent: ''
                            }
                        ],
                        columnDefs: [{
                                targets: 0,
                                searchable: false,
                                orderable: false,
                                visible: false,
                                className: "text-center"
                            },
                            {
                                targets: 1,
                                searchable: false,
                                orderable: false,
                                className: "text-center",
                                render: function(data, type, full, meta) {

                                    return ` <div class="d-flex justify-content-md-center">
                                                <div class="text-center">
                                                    <a href="#"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">${full.date_class}</a>
                                                    <span class="text-muted font-weight-bold d-block">${full.start_datetime} - ${full.end_datetime}</span>
                                                </div>
                                            </div>`;
                                }
                            },
                            {
                                targets: 2,
                                searchable: false,
                                orderable: false,
                                render: function(data, type, full, meta) {

                                    return ` <div class="d-flex align-items-center">
                                            <div>
                                                <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg text-center">${full.class_name}</a>
                                            </div>
                                        </div>`;
                                }
                            },
                            {
                                targets: 3,
                                searchable: false,
                                orderable: false,
                                render: function(data, type, full, meta) {

                                    return ` <div class="d-flex align-items-center">
                                            <div>
                                                <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg text-center">${full.package_type}</a>
                                            </div>
                                        </div>`;
                                }
                            },
                            {
                                targets: 4,
                                searchable: false,
                                orderable: false,
                                render: function(data, type, full, meta) {

                                    return ` <div class="d-flex align-items-center">
                                            <div>
                                                <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg text-center">${full.student_name}</a>
                                            </div>
                                        </div>`;
                                }
                            },
                            {
                                targets: 5,
                                searchable: false,
                                orderable: false,
                                render: function(data, type, full, meta) {

                                    if (moment(full.datetime) > moment() && moment() < moment(full.datetime).subtract(full.session_duration, "minutes")) {
                                        var status =  `Active`;
                                        var color = `primary`;
                                    } else if ( moment() > moment(full.datetime).subtract(full.session_duration, "minutes")) {
                                        var status =  `Completed`;
                                        var color = `success`;
                                    } else{
                                        var status =  `Cancel`;
                                        var color = `danger`;
                                    }

                                    return `
                                    <div class="d-flex align-items-center">
                                            <div>
                                                <a href="#" class="text-`+color+` font-weight-bolder text-hover-primary mb-1 font-size-lg text-center">`+status+`</a>
                                            </div>
                                        </div>
                                    `;

                                }

                            },
                            {
                                targets: -1,
                                searchable: false,
                                orderable: false,
                                data: "id",
                                render: function(data, type, full, meta) {
                                    return `
                                    <a href="{{ url('coach/dashboard/coach-show-review-last-class/${full.id}/${full.student_schedule_id}') }}" id="coach-show-last-class-btn-${full.id}" class="btn btn-primary coach-show-last-class-btn btn-sm btn-clean btn-icon" title="Student Review">
                                        <span class="svg-icon svg-icon-md">
                                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Visible.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                            </g>
                                            </svg><!--end::Svg Icon-->
                                        </span>
                                    </a>
                                    <a href="{{ url('coach/dashboard/show-last-class/${full.id}/${full.student_schedule_id}') }}" id="review-last-class-btn-${full.id}" class="btn btn-warning review-last-class-btn btn-sm btn-clean btn-icon" title="Review Student">
                                        <span class="svg-icon svg-icon-md">
                                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Visible.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                    <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                                    <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                                </g>
                                            </svg><!--end::Svg Icon-->
                                        </span>
                                    </a>`
                                }
                            },
                        ],
                        order: [
                            [1, 'asc']
                        ],
                        searching: true,
                        paging: true,
                        lengthChange: false,
                        bInfo: true,
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

                    $('#pageLength').on('change', function() {
                        init_table.page.len(this.value).draw();
                    });
                },
                initAction = () => {
                    $(document).on('change','#start_date',function(event){
                        event.preventDefault();
                        if($(this).val() != null || $(this).val() != ''){
                            if($('#end_date').val() != null || $('#end_date').val() != ''){
                                let date_start = moment($(this).val()).format('YYYY-MM-DD');
                                let date_end = moment($('#end_date').val()).format('YYYY-MM-DD');

                                if(moment(date_start).isSameOrBefore(date_end)){
                                    lastClassTable(date_start,date_end);
                                }
                            }
                        }
                    });

                    $(document).on('change','#end_date',function(event){
                        event.preventDefault();
                        if($(this).val() != null || $(this).val() != ''){
                            if($('#start_date').val() != null || $('#start_date').val() != ''){
                                let date_end = moment($(this).val()).format('YYYY-MM-DD');
                                let date_start = moment($('#start_date').val()).format('YYYY-MM-DD');

                                if(moment(date_start).isSameOrBefore(date_end)){
                                    lastClassTable(date_start,date_end);
                                }
                            }
                        }
                    })

                    $(document).on('click', '.review-last-class-btn', function(event) {
                        event.preventDefault();

                        var id_name = $(this).attr("id");

                        var data = init_table.row($(this).parents('tr')).data();
                        var student_schedule_id = data.student_schedule_id;
                        btn_loading_class_not_text(
                            id_name,
                            'start',
                            `<span class="svg-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                        <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                    </g>
                                </svg>
                            </span>`
                        );

                        $.ajax({
                                url: $(this).attr('href'),
                                type: 'get',
                                dataType: 'json',
                            })
                            .done(function(res, xhr, meta) {
                                $('#form-review-last-class').trigger("reset");
                                $('#form-review-last-class').attr('action',"{{ url('coach/dashboard/review-last-class') }}/" + res.data.detail_schedules.id+'&'+student_schedule_id);
                                $('#form-review-last-class').attr('method', 'POST');

                                $('.classroom-name').html(res.data.detail_schedules.class_name);
                                $('.student-name').html(res.data.detail_schedules.student_name);
                                $('.date').html(moment(res.data.detail_schedules.datetime).format('DD MMMM YYYY'));
                                $('.time').html(moment(res.data.detail_schedules.datetime).format('HH:MI'));

                                if(res.data.feedback.length > 0){
                                    $.each(res.data.feedback, function(index, data) {
                                        for (i = 1; i <= 5; i++) {
                                            $('#rate-'+i).prop('checked', false);
                                        }

                                        for (i = 1; i <= data.star; i++) {
                                            $('#rate-'+i).prop('checked', true);
                                        }

                                        $('#t-feedback').val(data.description)
                                    });
                                }


                                showModal('modal-review-last-class');
                            })

                            btn_loading_class_not_text(
                                id_name,
                                'stop',
                                `<span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                            <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                            <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                        </g>
                                    </svg>
                                </span>`
                            );

                    });

                    $(document).on('click', '.coach-show-last-class-btn', function(event) {
                        event.preventDefault();

                        var id_name = $(this).attr("id");

                        btn_loading_class_not_text(
                            id_name,
                            'start',
                            `<span class="svg-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                        <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                    </g>
                                </svg>
                            </span>`
                        );

                        $.ajax({
                                url: $(this).attr('href'),
                                type: 'get',
                                dataType: 'json',
                            })
                            .done(function(res, xhr, meta) {

                                showModal('modal-review');

                                $.each(res.data, function(index, data) {
                                    for (i = 1; i <= 5; i++) {
                                        $('#student-rate-'+i).prop('checked', false);
                                    }

                                    for (i = 1; i <= data.star; i++) {
                                        $('#student-rate-'+i).prop('checked', true);
                                    }

                                    $('#t-student-feedback').val(data.description)
                                });

                                btn_loading_class_not_text(
                                    id_name,
                                    'stop',
                                    `<span class="svg-icon svg-icon-md">
                                        <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Visible.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                        </g>
                                        </svg><!--end::Svg Icon-->
                                    </span>`
                                );
                            })
                    });

                    $(document).on('click', '.cancle-schedule-btn', function(event) {
                        event.preventDefault();

                        var id_name = $(this).attr("id");
                        var url = $(this).attr('href');

                        btn_loading_class(
                            id_name,
                            'start',
                            `Cancel`
                        );

                        Swal.fire({
                            title: 'Cancel Schedule?',
                            text: "Schedule Can be Cancel!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#7F16A7',
                            confirmButtonText: 'Yes, Delete',
                        }).then(function(result) {
                            if (result.value) {
                                $.ajax({
                                        url: url,
                                        type: 'DELETE',
                                        dataType: 'json',
                                    })
                                    .done(function(res, xhr, meta) {
                                        toastr.success(res.message, 'Success')

                                        closestScheduleTable();
                                        lastClassTable();

                                        btn_loading_class(
                                            id_name,
                                            'stop',
                                            `Cancel`
                                        );
                                    })
                                    .fail(function(res, error) {
                                        toastr.error(res.responseJSON.message, 'Failed')
                                    })
                                    .always(function() {});
                            }
                        })
                        $('.swal2-title').addClass('justify-content-center')
                    });

                    $(document).on('click', '.btn-withdraw', function(event) {
                        event.preventDefault();

                        getBalance();

                        $('#form-withdraw-request').trigger("reset");
                        showModal('modal-withdraw-request')

                    })
                },
                getBalance = () => {
                    $.ajax({
                        url: "{{url('coach/withdraw/get-balance')}}",
                        type: "GET",
                    })
                    .done(function(res, xhr, meta) {

                        $('#i-saldo').val('Rp. ' + numeral(res.data.amount).format('0,0'))
                        $('#i-amount').attr('max', res.data.amount)

                        $('.d-amount').html('Rp. ' + numeral(res.data.amount + res.data.balance).format('0,0'))
                        $('.d-balance').html('Rp. ' + numeral(res.data.balance).format('0,0'))

                    })
                },
                formSubmit = () => {
                    $('#form-review-last-class').submit(function(event) {
                        event.preventDefault();

                        let form_data = new FormData(this)

                        btn_loading('start')
                        $.ajax({
                                url: $(this).attr('action'),
                                type: $(this).attr('method'),
                                data: $(this).serialize(),
                            })
                            .done(function(res, xhr, meta) {
                                toastr.success(res.message, 'Success')
                                lastClassTable();

                                hideModal('modal-review-last-class');
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() {
                                btn_loading('stop')
                            });
                    });

                    $('#form-withdraw-request').submit(function(event) {
                        event.preventDefault();

                        btn_loading('start')
                        $.ajax({
                            url: "{{url('coach/withdraw/request')}}",
                            type: "POST",
                            data: $(this).serialize(),
                        })
                        .done(function(res, xhr, meta) {
                            toastr.success(res.message, 'Success');
                            getBalance();
                            hideModal('modal-withdraw-request');
                        })
                        .fail(function(res, error) {
                            toastr.error(res.responseJSON.message, 'Failed')
                        })
                        .always(function() {
                            btn_loading('stop')
                        });
                    });
                }

            const start_setup = (id, select_id) => {
                const btn = document.querySelector("button");
                const post = document.querySelector(".post");
                const widget = document.querySelector(".star-widget");
                const editBtn = document.querySelector(".edit");
                btn.onclick = () => {
                    widget.style.display = "none";
                    post.style.display = "block";
                    editBtn.onclick = () => {
                        widget.style.display = "block";
                        post.style.display = "none";
                    }
                    return false;
                }
            }
        };

        return {
            init: function() {
                _componentPage();
            }
        }

    }();

    document.addEventListener('DOMContentLoaded', function() {
        Page.init();
    });

</script>
