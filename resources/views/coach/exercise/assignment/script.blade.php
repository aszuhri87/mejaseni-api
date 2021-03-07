<script type="text/javascript">
    var Page = function() {
        var _componentPage = function() {
            var init_table, init_classroom, docsDropzone;
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

                get_classroom();

                $('#due_date').datetimepicker();

            });

            const initAction = () => {
                    $(document).on('click', '#add-btn', function(event) {
                        event.preventDefault();

                        $('#form-assignment').trigger("reset");
                        $('#form-assignment').attr('action', '{{ url('coach/exercise/assignment') }}');
                        $('#form-assignment').attr('method', 'POST');

                        get_classroom_coach();
                        get_session_coach();

                        $('#is-premium').attr('checked', false);
                        $('#i-price').prop('required', false);
                        $('.i-price').hide();

                        docsDropzone.removeAllFiles(true);

                        showModal('modal-assignment');
                    });

                    $(document).on('click', '.btn-delete', function(event) {
                        event.preventDefault();

                        var url = $(this).attr('href');

                        Swal.fire({
                            title: 'Delete assignment?',
                            text: "Deleted assignment will be permanently lost!",
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

                                        var classroom = $('#classroom').val();
                                        var session = $('#session').val();
                                        get_assignment_list(classroom, session)

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
                            .always(function() {});

                    });

                    $(document).on('click', '#show-btn', function(event) {
                        event.preventDefault();
                        btn_loading_basic('start', 'Tampilkan')

                        var classroom = $('#classroom').val();
                        var session = $('#session').val();

                        get_assignment_list(classroom, session)

                    });

                    $(document).on('change', '#classroom', function() {
                        get_session($('#classroom').val())
                    })

                    $(document).on('change', '#classroom_coach', function() {
                        get_session_coach($('#classroom_coach').val())
                    })
                },
                formSubmit = () => {
                    $('#form-assignment').submit(function(event) {
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

                                var classroom = $('#classroom').val();
                                var session = $('#session').val();
                                get_assignment_list(classroom, session)

                                arr_path = [];
                                hideModal('modal-assignment');
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() {
                                btn_loading('stop')
                            });
                    });
                }

            const get_classroom = () => {
                if (init_classroom) {
                        init_classroom.destroy();
                    }

                    init_classroom = new SlimSelect({
                        select: '#classroom'
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

                            $('#classroom').html(element);
                        })
                },
                get_session = (id, select_id) => {
                    btn_loading_basic('start','Tampilkan')
                    disable_action('session','start')

                    $.ajax({
                            url: '{{ url('public/get-session-name-coach') }}/' + id,
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

                            disable_action('session','stop')
                            btn_loading_basic('stop', 'Tampilkan')

                            $('#session').html(element);
                        })
                },
                get_assignment_list = (classroom_coach, session_coach) => {

                    $.ajax({
                            url: '{{ url('coach/exercise/assignment/list') }}/' + classroom_coach + '/' + session_coach,
                            type: 'GET',
                            dataType: 'json',
                        })
                        .done(function(res, xhr, meta) {
                            if (res.status == 200) {
                                let element = ``
                                if (res.data.assignment.length >= 0) {

                                    $.each(res.data.assignment, function(index, data) {

                                        element += `
                                        <div class="col-lg-4 mb-5">
                                            <div class="card">
                                                <div class="card-header ribbon ribbon-clip ribbon-right">
                                                    `;
                                        element += `
                                                <div class="row align-items-center">
                                                    <div class="col-2">
                                                        <img src="{{ asset('assets/images/pdf-file-extension.png') }}" width="50px" height="50px">
                                                    </div>
                                                    <div class="col-9">
                                                        <p style="margin-bottom: 0 !important"><strong>${data.name}</strong></p>
                                                        <span class="text-muted">${data.classroom_name}</span>
                                                    </div>
                                                    <div class="col">
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
                                                                        <a href="{{ url('/coach/exercise/assignment') }}/${data.id}" class="font-weight-bold text-primary btn-delete">Hapus</a>
                                                                    </li>
                                                                </ul>
                                                                <!--end::Navigation-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="card-body">
                                            <div class="row mb-5">
                                                <div class="col text-justify overflow-auto" style="height:80px !important">
                                                    ${data.description}
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col">
                                                    <table class="bordered-less">
                                                        <tr>
                                                            <td width="100px"><span class="text-muted">Coach</span></td>
                                                            <td>${data.coach_name}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100px"><span class="text-muted">Upload At</span></td>
                                                            <td>${moment(data.upload_date).format('DD MMMM YYYY')}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-12">`;
                                        if (data.is_premium) {
                                            if (data.is_buy) {
                                                element += `
                                                    <a target="_blank" href="${data.file_url}" class="btn btn-primary">
                                                        <span class="svg-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                <path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1"/>
                                                                <path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) "/>
                                                            </g>
                                                        </svg></span>
                                                        Download
                                                    </a>
                                                `;
                                            } else {
                                                element += `
                                                <a href="{{ url('student/add-to-cart') }}" class="btn btn-primary btn-buy" data-price="${data.price}" data-theory_name="${data.theory_name}" data-theory_id="${data.theory_id}">
                                                    <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Cart3.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                            <path d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z" fill="#000000"/>
                                                        </g>
                                                    </svg><!--end::Svg Icon--></span>
                                                    Buy Theory
                                                </a>`;
                                            }
                                        } else {
                                            element += `
                                                <a target="_blank" href="${data.file_url}" class="btn btn-primary">
                                                    <span class="svg-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                            <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1"/>
                                                            <path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) "/>
                                                        </g>
                                                    </svg></span>
                                                    Download
                                                </a>
                                            `;
                                        }
                                        element += `
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                                    });
                                } else {
                                    element = `
                                    <div class="col-12">
                                        <div class="card" style="width:100%">
                                            <div class="card-body text-center">
                                                <h4 class="text-muted">Assignment Not Available</h4>
                                            </div>
                                        </div>
                                    </div>`
                                }

                                $('.my-title').html('<h3>Exercise ' + res.data.classroom + ' - Pertemuan ' + res.data.session + '</h3>');
                                $('#assignment-list').html(element);
                                $('#card-assignment').css('display', '');
                                btn_loading_basic('stop', 'Tampilkan')

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
                    btn_loading_basic('start', 'Tampilkan')

                    $.ajax({
                            url: '{{ url('public/get-session-name-coach') }}/' + id,
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
                            btn_loading_basic('stop', 'Tampilkan')

                            $('#session_coach').html(element);
                        })
                }

            const initDropzone = () => {
                docsDropzone = new Dropzone("#kt_dropzone_2", {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('coach/exercise/assignment/file') }}",
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
                                        url: "{{ url('coach/exercise/assignment/file') }}/" +
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
