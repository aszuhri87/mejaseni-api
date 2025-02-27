<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, init_classroom_category, init_sub_classroom_category, init_coach, init_expertise;

            $(document).ready(function() {
                initTable();
                formSubmit();
                initAction();
                get_coach();
                get_expertise();
                get_classroom_category();
                get_sub_classroom_category();
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/master/courses/session-video/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'name' },
                        { data: 'sub_category' },
                        { data: 'coach' },
                        { data: 'count_theory' },
                        { data: 'price' },
                        { data: 'number' },
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
                            data: "name",
                            render: function(data, type, full, meta) {
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <a href="{{url('admin/master/courses/session-video')}}/${full.id}" class="text-primary text-hover-info mb-1 font-size-lg">${data}</a>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: 2,
                            data: "sub_category",
                            render: function(data, type, full, meta) {
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">${data}</p>
                                        <span class="text-muted">${full.category}</span>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: 3,
                            data: "coach",
                            render: function(data, type, full, meta) {
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">${data}</p>
                                        <span class="text-muted">${full.expertise}</span>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: 4,
                            data: "count_theory",
                            render: function(data, type, full, meta) {
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">${data}</p>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: 5,
                            data: "price",
                            render: function(data, type, full, meta) {
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">Rp. ${data}</p>
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
                            render : function(data, type, full, meta) {
                                return `
                                <a href="{{url('admin/master/courses/session-video')}}/${data}" title="Detail" class="btn btn-sm btn-clean btn-icon mr-2">
                                    <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Settings-1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z" fill="#000000"/>
                                            <path d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z" fill="#000000" opacity="0.3"/>
                                        </g>
                                    </svg><!--end::Svg Icon--></span>
                                </a>
                                @can('video_update')
                                    <a href="{{url('/admin/master/courses/session-video/update')}}/${data}" title="Edit" class="btn btn-edit btn-sm btn-clean btn-icon mr-2" title="Edit details">
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
                                @endcan
                                @can('video_delete')
                                    <a href="{{url('/admin/master/courses/session-video')}}/${data}" title="Delete" class="btn btn-delete btn-sm btn-clean btn-icon" title="Delete">
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
                                @endcan
                                    `
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

                    $('#form-session-video').trigger("reset");
                    $('#form-session-video').attr('action','{{url('admin/master/courses/session-video')}}');
                    $('#form-session-video').attr('method','POST');

                    get_coach();
                    get_expertise();
                    get_classroom_category();
                    get_sub_classroom_category();

                    $('#image').html('<input type="file" name="image" class="dropify image"/>');
                    $('.dropify').dropify();

                    showModal('modal-session-video');
                });

                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-session-video').trigger("reset");
                    $('#form-session-video').attr('action', $(this).attr('href'));
                    $('#form-session-video').attr('method','POST');

                    $('#form-session-video').find('input[name="name"]').val(data.name);
                    $('#form-session-video').find('input[name="price"]').val(data.price);
                    $('#form-session-video').find('textarea[name="description"]').val(data.description);
                    $('#form-session-video').find('input[name="number"]').val(data.number);

                    get_coach(data.coach_id);
                    get_expertise(data.expertise_id);
                    get_classroom_category(data.classroom_category_id);
                    get_sub_classroom_category(data.classroom_category_id, data.sub_classroom_category_id);

                    $('#image').empty();

                    if(data.image_url){
                        element = `<input type="file" name="image" class="dropify image"  data-default-file="${data.image_url}"/>`;
                    }else{
                        element = `<input type="file" name="image" class="dropify image"/>`;
                    }

                    $('#image').html(element);

                    $('.dropify').dropify();

                    showModal('modal-session-video');
                });

                $(document).on('click', '.btn-delete', function(event){
                    event.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Delete Video?',
                        text: "Deleted Video will be permanently lost!",
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

                $(document).on('change', '#classroom-category', function(){
                    if($('#classroom-category').val() != ""){
                        get_sub_classroom_category($('#classroom-category').val())
                    }else{
                        get_sub_classroom_category()
                    }
                })
            },
            formSubmit = () => {
                $('#form-session-video').submit(function(event){
                    event.preventDefault();

                    let validate = ss_validate([
                        'classroom-category',
                        'sub-classroom-category',
                        'coach-select',
                        'expertise-select'
                    ]);

                    if(!validate){
                        return false;
                    }

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
                        toastr.success(res.message, 'Success')
                        init_table.draw(false);
                        hideModal('modal-session-video');
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
                if(init_classroom_category){
                    init_classroom_category.destroy();
                }

                init_classroom_category = new SlimSelect({
                    select: '#classroom-category'
                })

                $.ajax({
                    url: '{{url('public/get-has-classroom-category')}}',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Select Class Category</option>`
                    $.each(res.data, function(index, data) {
                        if(id == data.id){
                            element += `<option value="${data.id}" selected>${data.name}</option>`;
                        }else{
                            element += `<option value="${data.id}">${data.name}</option>`;
                        }
                    });

                    $('#classroom-category').html(element);
                })
            },
            get_sub_classroom_category = (id, select_id) => {
                if(init_sub_classroom_category){
                    init_sub_classroom_category.destroy();
                }

                init_sub_classroom_category = new SlimSelect({
                    select: '#sub-classroom-category'
                })

                $.ajax({
                    url: '{{url('public/get-sub-classroom-category-by-category')}}/'+id,
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Select Sub Class Category</option>`

                    $.each(res.data, function(index, data) {
                        if(select_id == data.id){
                            element += `<option value="${data.id}" selected>${data.name}</option>`;
                        }else{
                            element += `<option value="${data.id}">${data.name}</option>`;
                        }
                    });

                    $('#sub-classroom-category').html(element);
                })
            },
            get_coach = (select_id) => {
                if(init_coach){
                    init_coach.destroy();
                }

                init_coach = new SlimSelect({
                    select: '#coach-select'
                })

                $.ajax({
                    url: '{{url('public/get-coach')}}',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Pilih Coach</option>`

                    $.each(res.data, function(index, data) {
                        if(select_id == data.id){
                            element += `<option value="${data.id}" selected>${data.name}</option>`;
                        }else{
                            element += `<option value="${data.id}">${data.name}</option>`;
                        }
                    });

                    $('#coach-select').html(element);
                })
            },
            get_expertise = (select_id) => {
                if(init_expertise){
                    init_expertise.destroy();
                }

                init_expertise = new SlimSelect({
                    select: '#expertise-select'
                })

                $.ajax({
                    url: '{{url('public/get-expertise')}}',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Pilih Expertise</option>`

                    $.each(res.data, function(index, data) {
                        if(select_id == data.id){
                            element += `<option value="${data.id}" selected>${data.name}</option>`;
                        }else{
                            element += `<option value="${data.id}">${data.name}</option>`;
                        }
                    });

                    $('#expertise-select').html(element);
                })
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
