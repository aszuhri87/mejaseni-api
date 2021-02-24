<script type="text/javascript">
    var Page = function() {
        var _componentPage = function() {
            var init_table, init_classroom_category, init_sub_classroom_category, init_classroom, docsDropzone;
            var arr_path = [];

            $(document).ready(function() {
                initAction();
                get_session();

                init_classroom_coach = new SlimSelect({
                    select: '#classroom_coach'
                })

                get_classroom_coach();

                $('#filter_date').datepicker({
                    rtl: KTUtil.isRTL(),
                    orientation: "bottom right",
                    todayHighlight: true,
                    templates: arrows
                });
            });

            const initAction = () => {

                $(document).on('click', '#show-table', function(event) {
                    event.preventDefault();
                    btn_loading_basic('start', 'Tampilkan')
                    initTable();
                    $('#card-review-assignment').css('display', '');
                    btn_loading_basic('stop', 'Tampilkan')
                });

                $(document).on('click', '#show-table-try', function(event) {
                    event.preventDefault();
                    showModal('modal-review-assignment');

                });

                $(document).on('click', '.show-review', function(event) {
                    event.preventDefault();

                    showModal('modal-review-assignment');
                });

                $(document).on('click', '.show-detail-review', function(event) {
                    event.preventDefault();

                    showModal('modal-detail-review-assignment');
                });

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
                initTable = () => {

                    var classroom_coach = $('#classroom_coach').val();
                    var session_coach = $('#session_coach').val();

                    init_table = $('#init-table').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        sScrollY: ($(window).height() < 700) ? $(window).height() -
                            200 : $(window)
                            .height() - 450,
                        ajax: {
                            type: 'POST',
                            url: "{{ url('coach/exercise/review-assignment/dt') }}/" +
                                classroom_coach + '/' + session_coach,
                        },
                        columns: [{
                                data: 'DT_RowIndex'
                            },
                            {
                                data: 'due_date'
                            },
                            {
                                data: 'upload_date'
                            },
                            {
                                data: 'name'
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
                                targets: 4,
                                searchable: false,
                                orderable: false,
                                className: "text-center",
                                render: function(data, type, full, meta) {

                                    if (full.status > 0) {
                                        return `
                                        <p class="text-success font-weight-bolder">Sudah Dinilai</p>
                                        `
                                    } else {
                                        return `
                                        <p class="text-warning font-weight-bolder">Belum Direview</p>
                                        `
                                    }
                                }
                            },
                            {
                                targets: -1,
                                searchable: false,
                                orderable: false,
                                className: "text-center",
                                data: "id",
                                render: function(data, type, full, meta) {
                                    if (full.status > 0) {
                                        var jenis = 'show-detail-review'
                                    } else {
                                        var jenis = 'show-review'
                                    }

                                    return `
                                    <a class="btn btn-light-primary btn-sm `+jenis+`" title="detail" href="{{ url('/coach/exercise/review-assignment') }}/${data}">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                    <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                                </g>
                                            </svg>
                                        </span>
                                         Lihat Detail
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
                            btn_loading_basic('stop', 'Tampilkan')

                            $('#session_coach').html(element);
                        })
                },
                start_setup = (id, select_id) => {
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
