<script type="text/javascript">
    var Page = function() {
        var _componentPage = function() {
            var init_table, init_classroom_category, init_sub_classroom_category, init_classroom, docsDropzone;
            var arr_path = [];

            $(document).ready(function() {
                initAction();
                get_session();
                formSubmit();

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

                    $(document).on('click', '.show-review', function(event) {
                        event.preventDefault();
                        var id_name = $(this).attr("id");

                        btn_loading_class(
                            id_name,
                            'start',
                            `<span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                </g>
                            </svg>
                        </span>
                        Lihat Detail`
                        );

                        $.ajax({
                                url: $(this).attr('href'),
                                type: 'get',
                                dataType: 'json',
                            })
                            .done(function(res, xhr, meta) {
                                $('#form-review-assignment').trigger("reset");
                                $('#form-review-assignment').attr('action',
                                    "{{ url('coach/exercise/review-assignment') }}/" + res
                                    .data.detail.id);
                                $('#form-review-assignment').attr('method', 'PUT');

                                $('.student-name').html(res.data.detail.name);
                                $('.classroom-name').html(res.data.detail.classroom);
                                $('.upload-at').html(res.data.detail.upload_date);
                                $('.description').html(res.data.detail.description);

                                $('input[name="collection_id"]').val(res.data.detail.id);
                                $('input[name="classroom_id"]').val(res.data.detail.classroom_id);
                                $('input[name="student_id"]').val(res.data.detail.student_id);

                                let element = ``

                                $.each(res.data.collection, function(index, data) {
                                    element +=
                                        `<div class="col-xl-3 text-center">
                                            <div class="card card-border card-custom bgi-no-repeat gutter-b shadow-sm rounded-lg"
                                                style="height: 220px; background-color: #ffffff; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto;">
                                                <!--begin::Body-->
                                                <div class="card-body d-flex align-items-center">
                                                    <div>
                                                        <span class="card-icon">
                                                            <div class="symbol symbol-circle symbol-60 mr-3">
                                                                <img alt="Pic"
                                                                    src="${res.data.detail.image_url}" />
                                                            </div>
                                                        </span>
                                                        <div class="card-label my-3 h5">PDF</div>
                                                        <a href='${data.file_url}' target="_blank" class="btn btn-primary font-weight-bold px-12">
                                                            <span class="svg-icon svg-icon-white">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
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
                                                        </a>
                                                    </div>
                                                </div>
                                                <!--end::Body-->
                                            </div>
                                        </div>`;

                                });

                                $('.collection').html(element);

                                showModal('modal-review-assignment');
                                btn_loading_class(
                                    id_name,
                                    'stop',
                                    `<span class="svg-icon svg-icon-primary svg-icon-2x">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                        </g>
                                    </svg>
                                </span>
                                    Lihat Detail`
                                );
                            })

                    });

                    $(document).on('click', '.show-detail-review', function(event) {
                        event.preventDefault();
                        var id_name = $(this).attr("id");

                        btn_loading_class(
                            id_name,
                            'start',
                            `<span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                        <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg>
                            </span>
                            Lihat Detail`
                        );

                        $.ajax({
                                url: $(this).attr('href'),
                                type: 'GET',
                                dataType: 'json',
                            })
                            .done(function(res, xhr, meta) {

                                $('#form-detail-review-assignment .description').html(res.data.detail.description);

                                let feedback = ``;
                                let collection_files = ``;

                                $.each(res.data.collection_feedback, function(index, data) {
                                    let rate = ``;

                                    for (i = 1; i < 5; i++) {
                                        if (i <= data.star) {
                                            rate += `<span class="svg-icon svg-icon-warning svg-icon-2x">
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                            viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <path
                                                                    d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z"
                                                                    fill="#000000" />
                                                            </g>
                                                        </svg>
                                                        <!--end::Svg Icon-->
                                                    </span>`;
                                        } else {
                                            rate += `<span class="svg-icon svg-icon-muted svg-icon-2x">
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                            viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <path
                                                                    d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z"
                                                                    fill="#000000" />
                                                            </g>
                                                        </svg>
                                                        <!--end::Svg Icon-->
                                                    </span>`;
                                        }
                                    }

                                    feedback +=
                                        ` <div class="col-12 col-sm-12 col-lg-12 col-xl-12">
                                            <!--begin::Item-->
                                            <div class="d-flex align-items-center pb-9">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-circle symbol-60  mr-3">
                                                    <img alt="Pic" src="${data.coache_image_url}" />
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Section-->
                                                <div class="d-flex flex-column flex-grow-1">
                                                    <!--begin::Title-->
                                                    <a href="#"
                                                        class="text-dark-75 font-weight-bolder font-size-lg text-hover-primary mb-1">${data.coache_name}</a>
                                                    <!--end::Title-->
                                                    <!--begin::rating-->
                                                    <span class="text-dark-50 font-weight-normal font-size-sm">
                                                        ` + rate + `
                                                    </span>
                                                    <!--begin::rating-->
                                                    <!--begin::Desc-->
                                                    <span class="text-dark-50 font-weight-normal font-size-sm">${data.description}</span>
                                                    <!--begin::Desc-->
                                                </div>
                                                <!--end::Section-->
                                            </div>
                                            <!--end::Item-->
                                        </div>`;

                                });

                            
                                $.each(res.data.collection_files, function(index, data) {
                                    collection_files +=
                                        `<div class="col-xl-3 text-center">
                                            <div class="card card-border card-custom bgi-no-repeat gutter-b shadow-sm rounded-lg"
                                                style="height: 220px; background-color: #ffffff; background-position: calc(100% + 0.5rem) 100%; background-size: 100% auto;">
                                                <!--begin::Body-->
                                                <div class="card-body d-flex align-items-center">
                                                    <div>
                                                        <span class="card-icon">
                                                            <div class="symbol symbol-circle symbol-60 mr-3">
                                                                <img alt="Pic"
                                                                    src="${data.image_url}" />
                                                            </div>
                                                        </span>
                                                        <div class="card-label my-3 h5">PDF</div>
                                                        <a href='${data.file_url}' target="_blank" class="btn btn-primary font-weight-bold px-12">
                                                            <span class="svg-icon svg-icon-white">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
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
                                                        </a>
                                                    </div>
                                                </div>
                                                <!--end::Body-->
                                            </div>
                                        </div>`;
                                });

                                $('.feedback').html(feedback);
                                $('.collection_file').html(collection_files);

                                showModal('modal-detail-review-assignment');
                                btn_loading_class(
                                    id_name,
                                    'stop',
                                    `<span class="svg-icon svg-icon-primary svg-icon-2x">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                            </g>
                                        </svg>
                                    </span>
                                        Lihat Detail`
                                );
                            })
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
                },
                formSubmit = () => {
                    $('#form-review-assignment').submit(function(event) {
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
                                init_table.draw(false);

                                hideModal('modal-review-assignment');
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
                                        var jenis = 'show-detail-review';
                                        var edit = '';
                                    } else {
                                        var jenis = 'show-review'
                                        var edit = 'edit';
                                    }

                                    return `
                                    <a id="` + jenis + `-` + full.id + `" class="btn btn-light-primary btn-sm ` +
                                        jenis +
                                        `" title="detail" href="{{ url('/coach/exercise/review-assignment') }}/${data}/` +
                                        edit + `">
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
