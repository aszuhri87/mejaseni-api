<script type="text/javascript">
    var Page = function() {
        var _componentPage = function() {
            var init_table, init_classroom_category, init_sub_classroom_category, init_classroom, docsDropzone;
            var arr_path = [];

            $(document).ready(function() {
                formSubmit();
                initAction();
                get_session();
                initDropzone();

                init_classroom = new SlimSelect({
                    select: '#classroom'
                })

                init_classroom_coach = new SlimSelect({
                    select: '#classroom_coach'
                })

                get_classroom_coach();

            });

            const  initAction = () => {
                    $(document).on('click', '#add-btn', function(event) {
                        event.preventDefault();

                        $('#form-theory').trigger("reset");
                        $('#form-theory').attr('action', '{{ url('coach/theory') }}');
                        $('#form-theory').attr('method', 'POST');

                        get_classroom_category();
                        get_sub_classroom_category();
                        get_session();

                        $('#is-premium').attr('checked', false);
                        $('#i-price').prop('required', false);
                        $('.i-price').hide();

                        docsDropzone.removeAllFiles(true);

                        showModal('modal-theory');
                    });

                    $(document).on('click', '.btn-delete', function(event) {
                        event.preventDefault();
                        var data = init_table.row($(this).parents('tr')).data();

                        var url = $(this).attr('href');

                        Swal.fire({
                            title: 'Delete Theory?',
                            text: "Deleted Theory will be permanently lost!",
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

                                        var classroom_coach = $('#classroom_coach').val();
                                        var session_coach = $('#session_coach').val();
                                        get_theory_list(classroom_coach, session_coach)

                                    })
                                    .fail(function(res, error) {
                                        toastr.error(res.responseJSON.message, 'Failed')
                                    })
                                    .always(function() {});
                            }
                        })
                        $('.swal2-title').addClass('justify-content-center')
                    });

                    $(document).on('click', '.btn-download', function(event) {
                        event.preventDefault();
                        var url = $(this).attr('href');

                        $.ajax({
                                url: url,
                                type: 'get',
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
                            });

                    });

                    $(document).on('click', '#show-btn', function(event) {
                        event.preventDefault();
                        btn_loading_basic('start','Tampilkan')

                        var classroom_coach = $('#classroom_coach').val();
                        var session_coach = $('#session_coach').val();

                        $.ajax({
                                url: '{{ url('coach/theory/list') }}/' + classroom_coach + '/' +
                                    session_coach,
                                type: 'GET',
                                dataType: 'json',
                            })
                            .done(function(res, xhr, meta) {
                                if (res.status == 200) {
                                    let element = ``

                                    $.each(res.data.theory, function(index, data) {

                                        if (data.is_premium) {
                                            var premium =
                                                `<i class="symbol-badge bg-warning"></i>`;

                                        } else {
                                            var premium = ``;

                                        }

                                        element += `<div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                <!--begin::Card-->
                                                <div class="card card-custom card-border">
                                                    <div class="card-header">
                                                        <div class="card-title font-weight-bolder">
                                                            <span class="card-icon">
                                                                <div class="symbol symbol-circle symbol-60  mr-3">
                                                                    <img alt="Pic" src="{{ asset('assets/media/users/300_1.jpg') }}" />
                                                                    ` + premium + `
                                                                </div>
                                                            </span>
                                                            <div class="card-label">${data.name}
                                                            <div class="font-size-sm text-muted mt-2">${data.classrooms_name}</div></div>
                                                        </div>
                                                        <div class="card-toolbar">
                                                            <div class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions"
                                                                data-placement="left">
                                                                <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    <i class="ki ki-bold-more-hor"></i>
                                                                </a>
                                                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                                    <!--begin::Navigation-->
                                                                    <ul class="navi navi-hover">
                                                                        <li class="navi-header font-weight-bold py-4">
                                                                            <a href="{{ url('/coach/theory') }}/${data.id}" class="font-weight-bold text-primary btn-delete">Hapus</a>
                                                                        </li>
                                                                    </ul>
                                                                    <!--end::Navigation-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        ${data.description}.
                                                        <div class="row mt-5">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <div class="d-flex mb-3">
                                                                    <span class="text-muted">Coach</span>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <span class="text-muted">Upload At</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="d-flex mb-3">
                                                                    <span class="text-dark">${data.coaches_name}</span>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <span class="text-dark">${data.upload_at}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-10">
                                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                                <div class="d-flex mb-3">
                                                                    <a href="{{ url('/coach/theory/download') }}/${data.id}" target="_blank" class="btn btn-white btn-icon w-auto px-2 waves-effect width-md waves-light ml-1"                                                                    
                                                                        <span class="svg-icon svg-icon-white svg-icon-2x">
                                                                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Files/Download.svg--><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                                                viewBox="0 0 24 24" version="1.1">
                                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                    <rect x="0" y="0" width="24" height="24" />
                                                                                    <path
                                                                                        d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z"
                                                                                        fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                                                    <rect fill="#000000" opacity="0.3"
                                                                                        transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) "
                                                                                        x="11" y="1" width="2" height="14" rx="1" />
                                                                                    <path
                                                                                        d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z"
                                                                                        fill="#000000" fill-rule="nonzero"
                                                                                        transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) " />
                                                                                </g>
                                                                            </svg>
                                                                            <!--end::Svg Icon-->
                                                                        </span>
                                                                        Download
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Card-->
                                            </div>`;
                                    });


                                    $('.my-title').html('<h3>Materi ' + res.data.classroom + ' - ' +
                                        res
                                        .data.session + '</h3>');
                                    $('#theory-list').html(element);
                                    $('#card-theory').css('display', '');
                                    btn_loading('stop')

                                }
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() {
                                btn_loading_basic('stop','Tampilkan')
                            });
                    });

                    $(document).on('change', '#is-premium', function() {
                        if ($('input[name="is_premium"]:checked').length == 1) {
                            $('.i-price').show();
                            $('#i-price').attr('required', true);
                        } else {
                            $('.i-price').hide();
                            $('#i-price').prop('required', false);
                        }
                    })

                    $(document).on('change', '#classroom-category', function() {
                        if ($('#classroom-category').val() == "") {
                            $('.required-classroom-category').show();
                            $('.select-sub-category').hide();
                            $('.parent-sub-category').removeClass('col-md-6');
                            get_session()
                            get_classroom()
                        } else {
                            $('.required-classroom-category').hide();
                            get_sub_classroom_category($('#classroom-category').val())
                        }
                    })

                    $(document).on('change', '#sub-classroom-category', function() {
                        get_classroom($('#classroom-category').val(), $('#sub-classroom-category')
                            .val())
                    })

                    $(document).on('change', '#classroom', function() {
                        get_session($('#classroom').val())
                    })

                    $(document).on('change', '#classroom_coach', function() {
                        get_session_coach($('#classroom_coach').val())
                    })
                },
                formSubmit = () => {
                    $('#form-theory').submit(function(event) {
                        event.preventDefault();

                        let form_data = new FormData(this)

                        if (arr_path.length > 0) {
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
                                hideModal('modal-theory');
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
                    if (init_classroom_category) {
                        init_classroom_category.destroy();
                    }

                    init_classroom_category = new SlimSelect({
                        select: '#classroom-category'
                    })

                    $.ajax({
                            url: '{{ url('public/get-classroom-category') }}',
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            let element = `<option value="">Select Class Category</option>`
                            $.each(res.data, function(index, data) {
                                if (id == data.id) {
                                    element +=
                                        `<option value="${data.id}" selected>${data.name}</option>`;
                                } else {
                                    element += `<option value="${data.id}">${data.name}</option>`;
                                }
                            });

                            $('#classroom-category').html(element);
                        })
                },
                get_sub_classroom_category = (id, select_id, on = true) => {
                    if (init_sub_classroom_category) {
                        init_sub_classroom_category.destroy();
                    }

                    init_sub_classroom_category = new SlimSelect({
                        select: '#sub-classroom-category'
                    })

                    $.ajax({
                            url: '{{ url('public/get-sub-classroom-category-by-category') }}/' + id,
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            if (res.data.length > 0) {
                                $('.select-sub-category').show();

                                let element = `<option value="">Select Sub Class Category</option>`

                                $.each(res.data, function(index, data) {
                                    if (select_id == data.id) {
                                        element +=
                                            `<option value="${data.id}" selected>${data.name}</option>`;
                                    } else {
                                        element +=
                                            `<option value="${data.id}">${data.name}</option>`;
                                    }
                                });

                                $('#sub-classroom-category').html(element);
                                $('.parent-sub-category').addClass('col-md-6');
                                get_classroom()
                            } else {
                                $('.select-sub-category').hide();
                                $('.parent-sub-category').removeClass('col-md-6');

                                if (on) {
                                    get_classroom($('#classroom-category').val())
                                }
                            }
                        })
                },
                get_classroom = (category_id, sub_category_id, select_id) => {
                    if (init_classroom) {
                        init_classroom.destroy();
                    }

                    init_classroom = new SlimSelect({
                        select: '#classroom'
                    })

                    if (category_id == '') {
                        category_id = undefined;
                    }

                    $.ajax({
                            url: '{{ url('public/get-classroom') }}/' + category_id + '&' +
                                sub_category_id,
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            let element = `<option value="">Select Class</option>`

                            $.each(res.data, function(index, data) {
                                if (select_id == data.id) {
                                    element +=
                                        `<option value="${data.id}" selected>${data.name}</option>`;
                                } else {
                                    element += `<option value="${data.id}">${data.name}</option>`;
                                }
                            });

                            $('#classroom').html(element);
                        })
                },
                get_classroom_edit = (category_id, sub_category_id, select_id) => {
                    if (init_classroom) {
                        init_classroom.destroy();
                    }

                    init_classroom = new SlimSelect({
                        select: '#classroom'
                    })

                    if (category_id == '') {
                        category_id = undefined;
                    }

                    $.ajax({
                            url: '{{ url('public/get-classroom') }}/' + category_id + '&' +
                                sub_category_id,
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            let element = `<option value="">Select Class</option>`

                            $.each(res.data, function(index, data) {
                                if (select_id == data.id) {
                                    element +=
                                        `<option value="${data.id}" selected>${data.name}</option>`;
                                } else {
                                    element += `<option value="${data.id}">${data.name}</option>`;
                                }
                            });

                            $('#classroom').html(element);
                        })
                },
                get_session = (id, select_id) => {
                    $.ajax({
                            url: '{{ url('public/get-session') }}/' + id,
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            let element = `<option value="">Select Session</option>`

                            if (res.data) {
                                for (let index = 0; index < parseInt(res.data.session_total); index++) {
                                    if (index + 1 == select_id) {
                                        element +=
                                            `<option value="${index + 1}" selected>Session ${index + 1}</option>`;
                                    } else {
                                        element +=
                                            `<option value="${index + 1}">Session ${index + 1}</option>`;
                                    }
                                }
                            }

                            $('#session').html(element);
                        })
                },
                get_theory_list = (classroom_coach, session_coach) => {

                    $.ajax({
                            url: '{{ url('coach/theory/list') }}/' + classroom_coach + '/' +
                                session_coach,
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            if (res.status == 200) {
                                let element = ``

                                $.each(res.data.theory, function(index, data) {

                                    if (data.is_premium) {
                                        var premium = `<i class="symbol-badge bg-warning"></i>`;

                                    } else {
                                        var premium = ``;

                                    }

                                    element += `<div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                <!--begin::Card-->
                                                <div class="card card-custom">
                                                    <div class="card-header">
                                                        <div class="card-title font-weight-bolder">
                                                            <span class="card-icon">
                                                                <div class="symbol symbol-circle symbol-60  mr-3">
                                                                    <img alt="Pic" src="{{ asset('assets/media/users/300_1.jpg') }}" />
                                                                    ` + premium + `
                                                                </div>
                                                            </span>
                                                            <div class="card-label">${data.name}
                                                            <div class="font-size-sm text-muted mt-2">${data.classrooms_name}</div></div>
                                                        </div>
                                                        <div class="card-toolbar">
                                                            <div class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions"
                                                                data-placement="left">
                                                                <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    <i class="ki ki-bold-more-hor"></i>
                                                                </a>
                                                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                                    <!--begin::Navigation-->
                                                                    <ul class="navi navi-hover">
                                                                        <li class="navi-header font-weight-bold py-4">
                                                                            <a href="{{ url('/coach/theory') }}/${data.id}" class="font-weight-bold text-danger btn-delete">Hapus</a>
                                                                        </li>
                                                                    </ul>
                                                                    <!--end::Navigation-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        ${data.description}.
                                                        <div class="row mt-5">
                                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                                                <div class="d-flex mb-3">
                                                                    <span class="text-muted">Coach</span>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <span class="text-muted">Upload At</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                                                <div class="d-flex mb-3">
                                                                    <span class="text-dark">${data.coaches_name}</span>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <span class="text-dark">${data.upload_at}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-10">
                                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                                <div class="d-flex mb-3">
                                                                    <a href="${data.file_url}" id="download-btn"
                                                                        class="btn btn-primary btn-icon w-auto px-2 waves-effect width-md waves-light ml-1">
                                                                        <span class="svg-icon svg-icon-white svg-icon-2x">
                                                                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Files/Download.svg--><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                                                viewBox="0 0 24 24" version="1.1">
                                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                    <rect x="0" y="0" width="24" height="24" />
                                                                                    <path
                                                                                        d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z"
                                                                                        fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                                                    <rect fill="#000000" opacity="0.3"
                                                                                        transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) "
                                                                                        x="11" y="1" width="2" height="14" rx="1" />
                                                                                    <path
                                                                                        d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z"
                                                                                        fill="#000000" fill-rule="nonzero"
                                                                                        transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) " />
                                                                                </g>
                                                                            </svg>
                                                                            <!--end::Svg Icon-->
                                                                        </span>
                                                                        Download
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Card-->
                                            </div>`;
                                });


                                $('.my-title').html('<h3>Materi ' + res.data.classroom + ' - ' +
                                    res
                                    .data.session + '</h3>');
                                $('#theory-list').html(element);
                                $('#card-theory').css('display', '');
                            }
                        })
                }

            const get_classroom_coach = () => {
                    if (init_classroom_coach) {
                        init_classroom_coach.destroy();
                    }

                    init_classroom_coach = new SlimSelect({
                        select: '#classroom_coach'
                    })

                    $.ajax({
                            url: '{{ url('public/get-classroom-coach') }}',
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            let element = `<option value="">Select Class</option>`

                            $.each(res.data, function(index, data) {
                                element += `<option value="${data.id}">${data.name}</option>`;
                            });

                            $('#classroom_coach').html(element);
                        })
                },
                get_session_coach = (id, select_id) => {
                    btn_loading_basic('start','Tampilkan')

                    $.ajax({
                            url: '{{ url('public/get-session-coach') }}/' + id,
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            let element = `<option value="">Select Session</option>`

                            if (res.data) {
                                for (let index = 0; index < parseInt(res.data.session_total); index++) {
                                    if (index + 1 == select_id) {
                                        element +=
                                            `<option value="${index + 1}" selected>Session ${index + 1}</option>`;
                                    } else {
                                        element +=
                                            `<option value="${index + 1}">Session ${index + 1}</option>`;
                                    }
                                }
                            }
                            btn_loading_basic('stop','Tampilkan')

                            $('#session_coach').html(element);
                        })
                }

            const initDropzone = () => {
                docsDropzone = new Dropzone("#kt_dropzone_2", {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('coach/theory/file') }}",
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
                    success: function(file, response) {
                        arr_path.push({
                            id: file.upload.uuid,
                            path_id: response.data.id,
                            path: response.data.path
                        })
                        $('.dz-remove').addClass('dz-style-custom');
                    },
                    removedfile: function(file) {
                        $.each(arr_path, function(index, arr_data) {
                            if (file.upload.uuid == arr_data['id']) {
                                arr_path.splice(index, 1);

                                $.ajax({
                                        url: "{{ url('coach/theory/file') }}/" +
                                            arr_data['path_id'],
                                        type: 'DELETE',
                                        dataType: 'json',
                                    })
                                    .done(function(res, xhr, meta) {

                                    })
                                    .fail(function(res, error) {
                                        toastr.error(res.responseJSON.message,
                                            'Failed')
                                    })
                            }
                        })

                        var _ref;
                        return (_ref = file.previewElement) != null ? _ref.parentNode
                            .removeChild(file.previewElement) : void 0;
                    },
                });
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
