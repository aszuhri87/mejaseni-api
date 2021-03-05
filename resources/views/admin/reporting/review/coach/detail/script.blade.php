<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
    integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    var Page = function() {
        var _componentPage = function() {
            var init_table;
            var arr_tools = [];

            $(document).ready(function() {
                initTable();
                initAction();
                get_classroom("{{ $coach->id }}");
            });

            const initTable = (classroom, star, search) => {

                    if (classroom == '') {
                        classroom = undefined;
                    }

                    if (star == '') {
                        star = undefined;
                    }

                    if (search == '') {
                        search = undefined;
                    }

                    init_table = $('#init-table').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window)
                            .height() - 450,
                        ajax: {
                            type: 'POST',
                            url: "{{ url('admin/report/review/coach/detail/dt') }}/{{ $coach->id }}",
                            data: {
                                'classroom': classroom,
                                'star': star,
                                'search': search,
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex'
                            },
                            {
                                data: 'created_at'
                            },
                            {
                                data: 'student_name'
                            },
                            {
                                data: 'star'
                            },
                            {
                                data: 'description'
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
                                className: "text-center"
                            },
                            {
                                targets: 1,
                                data: "created_at",
                                render: function(data, type, full, meta) {
                                    return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">${moment(data).format('dddd, DD MMMM YYYY')}</p>
                                        <span class="text-muted">${moment(data).format('LT')}</span>
                                    </div>
                                `;
                                }
                            },
                            {
                                targets: 2,
                                data: "student_name",
                                render: function(data, type, full, meta) {
                                    return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">${data}</p>
                                        <span class="text-muted">${data }</span>
                                    </div>
                                `;
                                }
                            },
                            {
                                targets: 3,
                                data: "star",
                                className: "text-center",
                                render: function(data, type, full, meta) {

                                    var rate = ``

                                    for (let i = 1; i <= 5; i++) {
                                        if (data >= i) {
                                            rate += `<span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>`

                                        } else {

                                            rating = data - i
                                            if (rating <= 0) {
                                                rate += `<span class="svg-icon svg-icon-muted"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                    <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                                </g>
                                            </svg><!--end::Svg Icon--></span>`
                                            } else if (rating > 0) {
                                                rate += `<span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Half-star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                    <path d="M12,4.25932872 C12.1488635,4.25921584 12.3000368,4.29247316 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 L12,4.25932872 Z" fill="#000000" opacity="0.3"/>
                                                    <path d="M12,4.25932872 L12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.277344,4.464261 11.6315987,4.25960807 12,4.25932872 Z" fill="#000000"/>
                                                </g>
                                            </svg><!--end::Svg Icon--></span>`
                                            }
                                        }
                                    }
                                    return `
                                    <div class="d-flex flex-column font-weight-bold text-center">
                                        <p class="mb-1 font-size-lg">
                                            ` + rate + `
                                        </p>
                                        <span class="text-muted">Base Rated</span>
                                    </div>
                                
                                `;
                                }
                            },
                            {
                                targets: 5,
                                data: "status",
                                className: "text-center",
                                render: function(data, type, full, meta) {

                                    if (moment(full.datetime) > moment() && moment() < moment(full.datetime).subtract(full.session_duration, "minutes")) {
                                       var status =  `Active`;
                                       var color = `primary`;
                                    } else if ( moment() > moment(full.datetime).subtract(full.session_duration, "minutes")) {
                                        var status =  `Completed`;
                                        var color = `success`;
                                    } else{
                                        var status =  `Cancle`;
                                        var color = `danger`;
                                    }

                                    return `
                                    <div class="d-flex flex-column font-weight-bold text-center">
                                        <p class="mb-1 font-size-lg">
                                            <span class="label label-xl label-light-`+color+` label-inline mr-2">`+status+`</span>
                                        </p>
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
                                render: function(data, type, full, meta) {
                                    return `
                                    <a href="{{ url('/admin/report/review/coach/details') }}/${data}" class="btn btn-detail btn-sm btn-clean btn-icon mr-2" title="Details">
                                        <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Visible.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                    <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                                </g>
                                            </svg><!--end::Svg Icon-->
                                        </span>
                                    </a>
                                    `
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

                    $(document).on('click', '.btn-detail', function(event) {
                        event.preventDefault();

                        var data = init_table.row($(this).parents('tr')).data();
                        var rate = ``

                        for (let i = 1; i <= 5; i++) {
                            if (data.star >= i) {
                                rate += `<span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon--></span>`

                            } else {

                                rating = data - i
                                if (rating <= 0) {
                                    rate += `<span class="svg-icon svg-icon-muted"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>`
                                } else if (rating > 0) {
                                    rate += `<span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Half-star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M12,4.25932872 C12.1488635,4.25921584 12.3000368,4.29247316 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 L12,4.25932872 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M12,4.25932872 L12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.277344,4.464261 11.6315987,4.25960807 12,4.25932872 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>`
                                }
                            }
                        }

                        var star_rating = `
                            <div class="d-flex flex-column font-weight-bold">
                                <p class="mb-1 font-size-lg">
                                    ` + rate + `
                                </p>
                            </div>`;

                        $('.tanggal').html(moment(data.created_at).format('dddd, DD MMMM YYYY'));
                        $('.siswa').html(data.student_name);
                        $('.siswa').html(data.student_name);
                        $('.rating').html(star_rating);
                        $('.text').html(data.description);

                        showModal('modal-report-review-coach');
                    });

                    $(document).on('change', '#classroom', function(event) {
                        event.preventDefault();

                        var classroom = $('select[name="classroom_id"]').val()
                        var star = $('select[name="star"]').val()
                        var search = $('input[name="search"]').val()

                        initTable(classroom, star, search);
                    });

                    $(document).on('change', '#star', function(event) {
                        event.preventDefault();

                        var classroom = $('select[name="classroom_id"]').val()
                        var star = $('select[name="star"]').val()
                        var search = $('input[name="search"]').val()

                        initTable(classroom, star, search);
                    });

                    $(document).on('change', '#search', function(event) {
                        event.preventDefault();

                        var classroom = $('select[name="classroom_id"]').val()
                        var star = $('select[name="star"]').val()
                        var search = $('input[name="search"]').val()

                        initTable(classroom, star, search);
                    });
                }

            const get_classroom = (coach_id) => {

                if (coach_id == '') {
                    coach_id = undefined;
                }

                $.ajax({
                        url: '{{ url('admin/report/review/coach/detail/get-classroom') }}/' +
                            coach_id,
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
