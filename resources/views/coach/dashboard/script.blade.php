<script type="text/javascript">
    var Page = function() {
        var _componentPage = function() {
            var init_table;

            $(document).ready(function() {
                summary_course_chart();
                side_summary_course();
                initTable();
                formSubmit();
                initAction();
                initTreeTable();
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
                                            columnWidth: ['12%'],
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
                                                    'gray-500'],
                                                fontSize: '12px',
                                                fontFamily: KTApp.getSettings()['font-family']
                                            }
                                        }
                                    },
                                    yaxis: {
                                        min: -80,
                                        max: 80,
                                        labels: {
                                            style: {
                                                colors: KTApp.getSettings()['colors']['gray'][
                                                    'gray-500'],
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
                                                return "$" + val + " thousands"
                                            }
                                        }
                                    },
                                    colors: [KTApp.getSettings()['colors']['theme']['base']['warning'],
                                        KTApp.getSettings()['colors']['theme']['base']['primary']
                                    ],
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
                side_summary_course = () => {
                    $.ajax({
                            url: `{{ url('coach/dashboard/side-summary-course') }}`,
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            if (res.status == 200) {
                                $('.total-kelas').text(res.data.total_kelas)
                                $('.video-tutorial').text(res.data.video_tutorial)
                                $('.booking-saat-ini').text(res.data.video_tutorial)
                                $('.riwayat-booking').text(res.data.video_tutorial)
                            }
                        })
                        .fail(function(res, error) {
                            toastr.error(res.responseJSON.message, 'Failed')
                        })
                        .always(function() {

                        });
                },
                initTable = () => {
                    init_table = $('#init-table').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window)
                            .height() - 450,
                        ajax: {
                            type: 'POST',
                            url: "{{ url('admin/master/admin/dt') }}",
                        },
                        columns: [{
                                data: 'DT_RowIndex'
                            },
                            {
                                data: 'name'
                            },
                            {
                                data: 'tipe_admin'
                            },
                            {
                                data: 'username'
                            },
                            {
                                data: 'email'
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
                                targets: -1,
                                searchable: false,
                                orderable: false,
                                className: "text-center",
                                data: "id",
                                render: function(data, type, full, meta) {
                                    return `
                                    <a class="btn btn-sm btn-permission mr-1 btn-clean btn-icon" title="Permission" href="{{ url('/admin/master/admin/permission') }}/${data}">
                                        <span class="svg-icon svg-icon-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <polygon fill="#000000" opacity="0.3" transform="translate(8.885842, 16.114158) rotate(-315.000000) translate(-8.885842, -16.114158) " points="6.89784488 10.6187476 6.76452164 19.4882481 8.88584198 21.6095684 11.0071623 19.4882481 9.59294876 18.0740345 10.9659914 16.7009919 9.55177787 15.2867783 11.0071623 13.8313939 10.8837471 10.6187476"/>
                                                <path d="M15.9852814,14.9852814 C12.6715729,14.9852814 9.98528137,12.2989899 9.98528137,8.98528137 C9.98528137,5.67157288 12.6715729,2.98528137 15.9852814,2.98528137 C19.2989899,2.98528137 21.9852814,5.67157288 21.9852814,8.98528137 C21.9852814,12.2989899 19.2989899,14.9852814 15.9852814,14.9852814 Z M16.1776695,9.07106781 C17.0060967,9.07106781 17.6776695,8.39949494 17.6776695,7.57106781 C17.6776695,6.74264069 17.0060967,6.07106781 16.1776695,6.07106781 C15.3492424,6.07106781 14.6776695,6.74264069 14.6776695,7.57106781 C14.6776695,8.39949494 15.3492424,9.07106781 16.1776695,9.07106781 Z" fill="#000000" transform="translate(15.985281, 8.985281) rotate(-315.000000) translate(-15.985281, -8.985281) "/>
                                                <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </a>
                                    <a href="{{ url('/admin/master/admin') }}/${data}" title="Edit" class="btn btn-edit btn-sm btn-clean btn-icon mr-2" title="Edit details">
                                        <span class="svg-icon svg-icon-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
                                                    <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
                                                </g>
                                            </svg>
                                        </span>
                                    </a>
                                    <a href="{{ url('/admin/master/admin') }}/${data}" title="Delete" class="btn btn-delete btn-sm btn-clean btn-icon" title="Delete">
                                        <span class="svg-icon svg-icon-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
                                                    <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                                </g>
                                            </svg>
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
                    $(document).on('click', '#add-btn', function(event) {
                        event.preventDefault();

                        $('#form-admin').trigger("reset");
                        $('#form-admin').attr('action', '{{ url('admin/master/admin') }}');
                        $('#form-admin').attr('method', 'POST');
                        $('.change_password').hide();
                        $('.change_role').hide();
                        $('input[type=password]').attr('required');
                        $('#password').css('display', '');
                        $('input[type=role]').attr('required');
                        $('#role').css('display', '');
                        showModal('modal-admin');
                    });

                    $(document).on('click', '.btn-edit', function(event) {
                        event.preventDefault();

                        var data = init_table.row($(this).parents('tr')).data();

                        $('#form-admin').trigger("reset");
                        $('#form-admin').attr('action', $(this).attr('href'));
                        $('#form-admin').attr('method', 'PUT');

                        $('#form-admin').find('input[name="name"]').val(data.name);
                        $('#form-admin').find('input[name="username"]').val(data.username);
                        $('#form-admin').find('input[name="email"]').val(data.email);
                        $(`#${data.role_id}`).attr('checked', true);

                        $('.change_password').show();
                        $('.change_role').show();

                        $('#change_password').attr('checked', true)
                        $('input[type=password]').attr('required');
                        $('#password').css('display', '');

                        $('#change_role').attr('checked', true)
                        $('input[type=role]').attr('required');
                        $('#role').css('display', '');

                        showModal('modal-admin');
                    });

                    $(document).on('click', '.btn-delete', function(event) {
                        event.preventDefault();
                        var url = $(this).attr('href');

                        Swal.fire({
                            title: 'Delete Admin?',
                            text: "Deleted Admin will be permanently lost!",
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
                                        init_table.draw(false);
                                    })
                                    .fail(function(res, error) {
                                        toastr.error(res.responseJSON.message, 'Failed')
                                    })
                                    .always(function() {});
                            }
                        })
                        $('.swal2-title').addClass('justify-content-center')
                    });

                    $(document).on('click', '.btn-permission', function(event) {
                        event.preventDefault();

                        $('#form_permission').attr('action', $(this).attr('href'));
                        showModal('modal_permission');

                        $.ui.fancytree.getTree("#tree-table").visit(function(node) {
                            if (node.expanded == true && node.children != null) {
                                node.toggleExpanded();
                            }
                        });

                        $('input[type=checkbox]').prop('checked', false);

                        setChecked($(this).attr('href'));
                    });

                    $(document).on('click', '.btn-config', function(event) {
                        event.preventDefault();
                        init_number_input = 1;

                        var data = init_table.row($(this).parents('tr')).data();

                        $('#form-config').trigger("reset");
                        $('#form-config').attr('action', $(this).attr('href'));
                        $('#form-config').attr('method', 'POST');
                        $('.other-package').hide();
                        $('.add-package').show();
                        $('#selectdisplay2').attr('required', false);
                        selectdisplay1.set([]);
                        selectdisplay2.set([]);
                        let init_package_type = 0;
                        let package_type_1, package_type_2;
                        let increment = 0;
                        $.ajax({
                                url: `{{ url('admin/master/admin/class') }}/${data.id}`,
                                type: `GET`,
                            })
                            .done(function(res, xhr, meta) {

                                $.each(res.data, function(index, data) {
                                    if (init_package_type == 0) {
                                        init_package_type = data.package_type
                                        data1[index] = data.classroom_id;
                                        package_type_1 = data.package_type;
                                    } else if (init_package_type == data.package_type) {
                                        data1[index] = data.classroom_id;
                                    } else {
                                        if (increment == 0) {
                                            $('.other-package').show();
                                            package_type_2 = data.package_type;

                                        }
                                        data2[increment] = data.classroom_id;
                                        increment++;
                                    }
                                });
                                if (package_type_1) {
                                    $('#package1').val(package_type_1).change();
                                }
                                if (package_type_2) {
                                    $('#package2').val(package_type_2).change();
                                }
                            })
                            .fail(function(res, error) {
                                if (res.status == 400 || res.status == 422) {
                                    $.each(res.responseJSON.errors, function(index, err) {
                                        if (Array.isArray(err)) {
                                            $.each(err, function(index, val) {
                                                toastr.error(val, 'Failed')
                                            });
                                        } else {
                                            toastr.error(err, 'Failed')
                                        }
                                    });
                                } else {
                                    toastr.error(res.responseJSON.message, 'Failed')
                                }
                            })
                            .always(function() {

                            });

                        showModal('modal-config');
                    });

                    $(document).on('click', '.btn-change-password', function(event) {

                        if ($(this).prop('checked') == true) {

                            $('input[type=password]').attr('required');
                            $('#password').css('display', '');
                        } else {
                            $('input[type=password]').removeAttr('required');
                            $('#password').css('display', 'none');
                        }
                    });

                    $(document).on('click', '.btn-change-role', function(event) {

                        if ($(this).prop('checked') == true) {

                            $('input[type=password]').attr('required');
                            $('#role').css('display', '');
                        } else {
                            $('input[type=role]').removeAttr('required');
                            $('#role').css('display', 'none');
                        }
                    });
                },
                formSubmit = () => {
                    $('#form-admin').submit(function(event) {
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
                                hideModal('modal-admin');
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() {
                                btn_loading('stop')
                            });
                    });

                    $('#form_permission').submit(function(event) {
                        event.preventDefault();

                        btn_loading('start')
                        $.ajax({
                                url: $(this).attr('action'),
                                type: $(this).attr('method'),
                                data: $(this).serialize(),
                            })
                            .done(function(res, xhr, meta) {
                                if (res.status == 200) {
                                    toastr.success(res.message, 'Success')
                                    init_table.draw(false);
                                    hideModal('modal_permission');
                                }
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Gagal')
                            })
                            .always(function() {
                                btn_loading('stop')
                            });
                    });

                    $('#form-config').submit(function(event) {
                        event.preventDefault();
                        btn_loading('start')
                        $.ajax({
                                url: $(this).attr('action'),
                                type: $(this).attr('method'),
                                data: new FormData(this),
                                contentType: false,
                                cache: false,
                                processData: false,
                            })
                            .done(function(res, xhr, meta) {
                                if (res.status == 200) {
                                    toastr.success(res.message, 'Success');
                                    init_table.draw(false);
                                    hideModal('modal-config');
                                }
                            })
                            .fail(function(res, error) {
                                if (res.status == 400 || res.status == 422) {
                                    $.each(res.responseJSON.errors, function(index, err) {
                                        if (Array.isArray(err)) {
                                            $.each(err, function(index, val) {
                                                toastr.error(val, 'Failed')
                                            });
                                        } else {
                                            toastr.error(err, 'Failed')
                                        }
                                    });
                                } else {
                                    toastr.error(res.responseJSON.message, 'Failed')
                                }
                            })
                            .always(function() {
                                btn_loading('stop')
                            });
                    });
                }
        };

        const initTreeTable = () => {
                $('#tree-table').fancytree({
                    extensions: ['table'],
                    checkbox: false,
                    icon: false,
                    table: {
                        nodeColumnIdx: 0,
                    },
                    source: {
                        url: '{{ asset('data/admin.json') }}'
                    },
                    lazyLoad: function(event, data) {
                        data.result = {
                            url: '{{ asset('data/admin.json') }}'
                        }
                    },
                    renderColumns: function(event, data) {
                        var node = data.node;

                        $tdList = $(node.tr).find('>td');

                        $tdList.eq(0).addClass('text-left');
                        $tdList.eq(1).addClass('text-center').html(
                            `<input type="checkbox" class="form-input-styled permissions ${node.key}" data-parent="${node.parent.key}" name="permissions[]" value="${node.key}" id="${node.key}">`
                        );
                        if (node.data.crud) {
                            $tdList.eq(2).addClass(`text-center`).html(
                                `<input type="checkbox" class="form-input-styled permissions ${node.key}_crud" name="permissions[]" value="${node.key}_list" data-parent="${node.key}">`
                            );
                            $tdList.eq(3).addClass(`text-center`).html(
                                `<input type="checkbox" class="form-input-styled permissions ${node.key}_crud" name="permissions[]" value="${node.key}_insert" data-parent="${node.key}">`
                            );
                            $tdList.eq(4).addClass(`text-center`).html(
                                `<input type="checkbox" class="form-input-styled permissions ${node.key}_crud" name="permissions[]" value="${node.key}_update" data-parent="${node.key}">`
                            );
                            $tdList.eq(5).addClass(`text-center`).html(
                                `<input type="checkbox" class="form-input-styled permissions ${node.key}_crud" name="permissions[]" value="${node.key}_delete" data-parent="${node.key}">`
                            );
                            $tdList.eq(6).addClass(`text-center`).html(
                                `<input type="checkbox" class="form-input-styled permissions ${node.key}_crud" name="permissions[]" value="${node.key}_print" data-parent="${node.key}">`
                            );
                        }

                        if (node.data.other) {

                            var element = ``;

                            $.each(node.data.other, function(key, val) {
                                element += `
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input-styled permissions ${node.key}_crud" name="permissions[]" value="${key}" data-parent="${node.key}" data-fouc>&nbsp;&nbsp;
                                        ${val}
                                    </label>
                                </div>
                            `;
                            });
                            $tdList.eq(7).addClass('text-left').html(`
                            <div class="d-flex align-items-center justify-content-start">
                                ${element}
                            </div>
                        `);
                        }

                        $tdList.addClass('pt-1 pb-1');

                    }
                });

                $('#tree-table').on('change', 'input[name="permissions[]"]', function(e) {
                    $input = $(e.target);

                    var value = $input.val();

                    var parent = $input.data('parent');

                    if ($('#tree-table').find(`[data-parent="${value}"]`).length > 0) {
                        if ($input.is(':checked')) {
                            var val = $input.val();
                            $('#tree-table').find(`[data-parent="${val}"]`).prop('checked', true).trigger(
                                'change');
                        } else {
                            var val = $input.val();
                            $('#tree-table').find(`[data-parent="${val}"]`).prop('checked', false).trigger(
                                'change');
                        }
                    } else {
                        if ($input.is(':checked')) {
                            var val = $input.val();
                            $('#tree-table').find(`[data-parent="${val}"]`).prop('checked', true);
                        } else {
                            var val = $input.val();
                            $('#tree-table').find(`[data-parent="${val}"]`).prop('checked', false);
                        }
                    }
                });

                $('#tree-table').on('click', 'input[name="permissions[]"]', function(e) {
                    $input = $(e.target);

                    var value = $input.val();

                    var parent = $input.data('parent');

                    if (typeof parent != 'undefined') {
                        if (parent != 'root_1') {

                            parent_parent = $(`#${parent}`).data('parent');

                            if ($input.is(':checked')) {
                                $('#tree-table').find(`.${parent}`).prop('checked', true);
                            } else {
                                if ($('#tree-table').find(`[data-parent="${parent}"]:checked`).length <=
                                    0) {
                                    $('#tree-table').find(`.${parent}`).prop('checked', false);
                                }
                            }

                            if (typeof parent_parent != 'undefined') {
                                if (parent_parent != 'root_1') {
                                    if ($('#tree-table').find(`[data-parent="${parent_parent}"]:checked`)
                                        .length > 0) {
                                        $('#tree-table').find(`.${parent_parent}`).prop('checked', true);
                                    } else {
                                        if ($('#tree-table').find(
                                                `[data-parent="${parent_parent}"]:checked`).length <= 0) {
                                            $('#tree-table').find(`.${parent_parent}`).prop('checked',
                                                false);
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            },
            setChecked = (url) => {
                $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                    })
                    .done(function(res, xhr, meta) {
                        if (res.status == 200) {
                            let val;
                            $.each(res.data, function(index, data) {
                                val = `:checkbox[value=${data.name}]`;
                                $(val).prop("checked", "true");
                            });
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Gagal')
                    })
                    .always(function() {});
            }

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
