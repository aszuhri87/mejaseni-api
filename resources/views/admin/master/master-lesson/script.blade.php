<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table,
                init_classroom_category,
                init_sub_classroom_category,
                docsDropzone,
                init_platform,
                init_guest_star,
                global_id;

            var arr_path = [],
                arr_guest = [],
                arr_guest_name = [];

            $(document).ready(function() {
                initTable();
                formSubmit();
                initAction();
                initDropzone();
                get_platform();
                get_classroom_category();
                get_guest_star();
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/master/courses/master-lesson/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'name' },
                        { data: 'category' },
                        { data: 'datetime' },
                        { data: 'slot' },
                        { data: 'price' },
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
                            render : function(data, type, full, meta) {
                                return `
                                        <div class="d-flex align-items-center">
                                            <div class="mr-5">
                                                <img src="${full.image_url}" class="rounded" width="50" height="50"/>
                                            </div>
                                            <div class="d-flex flex-column font-weight-bold">
                                                <p class="mb-1 font-size-lg">${data}</p>
                                            </div>
                                        </div>
                                `;
                            }
                        },
                        {
                            targets: 2,
                            data: "category",
                            render: function(data, type, full, meta) {
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">${data}</p>
                                        <span class="text-muted">${full.sub_category ? full.sub_category : '-'}</span>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: 3,
                            data: "datetime",
                            render: function(data, type, full, meta) {
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">${moment(data).format('DD MMMM YYYY')}</p>
                                        <span class="text-muted">${moment(data).format('HH:mm')}</span>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: 4,
                            data: "slot",
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
                                @can('master_lesson_update')
                                    <a href="{{url('/admin/master/courses/master-lesson/update')}}/${data}" title="Edit" class="btn btn-edit btn-sm btn-clean btn-icon mr-2" title="Edit details">
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
                                @can('master_lesson_delete')
                                    <a href="{{url('/admin/master/courses/master-lesson')}}/${data}" title="Delete" class="btn btn-delete btn-sm btn-clean btn-icon" title="Delete">
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

                    global_id = '';

                    $('#form-master-lesson').trigger("reset");
                    $('#form-master-lesson').attr('action','{{url('admin/master/courses/master-lesson')}}');
                    $('#form-master-lesson').attr('method','POST');

                    showModal('modal-master-lesson');
                });

                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();
                    global_id = data.id;

                    $('#form-master-lesson').trigger("reset");
                    $('#form-master-lesson').attr('action', $(this).attr('href'));
                    $('#form-master-lesson').attr('method','POST');

                    $('#form-master-lesson').find('input[name="date"]').val(moment(data.datetime).format('DD MMMM YYYY'));
                    $('#form-master-lesson').find('input[name="time"]').val(moment(data.datetime).format('HH:mm:ss'));
                    $('#form-master-lesson').find('input[name="name"]').val(data.name);
                    $('#form-master-lesson').find('input[name="slot"]').val(data.slot);
                    $('#form-master-lesson').find('input[name="price"]').val(data.price);
                    $('#form-master-lesson').find('input[name="platform_link"]').val(data.platform_link);
                    $('#form-master-lesson').find('textarea[name="description"]').val(data.description);

                    if(data.buy_btn_disable){
                        $('#switch-buy-btn-disable').attr('checked', 'checked');
                    }else{
                        $('#switch-buy-btn-disable').removeAttr('checked');
                    }

                    if(data.hide){
                        $('#switch-hide').attr('checked', 'checked');
                    }else{
                        $('#switch-hide').removeAttr('checked');
                    }

                    arr_guest = [];
                    initDataGuest(global_id);
                    get_classroom_category(data.classroom_category_id)
                    get_sub_classroom_category(data.classroom_category_id, data.sub_classroom_category_id)
                    get_platform(data.platform_id)

                    docsDropzone.removeAllFiles( true );

                    var mockFile = {
                        name: data.poster,
                        accepted: true
                    };

                    docsDropzone.files.push(mockFile);
                    docsDropzone.emit("addedfile", mockFile);
                    docsDropzone.emit("thumbnail", mockFile, data.image_url);
                    docsDropzone.emit("complete", mockFile);

                    showModal('modal-master-lesson');
                });

                $(document).on('click', '.btn-delete', function(event){
                    event.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Delete Master Lesson?',
                        text: "Deleted Master Lesson will be permanently lost!",
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

                $(document).on('click','.btn-delete-guest',function(){
                    if($(this).attr('id') == '-'){
                        let attr_data = $(this).attr('data');

                        Swal.fire({
                            title: 'Delete Guest?',
                            text: "Deleted Guest will be permanently lost!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#7F16A7',
                            confirmButtonText: 'Yes, Delete',
                        }).then(function (result) {
                            if (result.value) {
                                const index = arr_guest.indexOf(attr_data);
                                if (index > -1) {
                                    arr_guest.splice(index, 1);
                                    arr_guest_name.splice(index, 1);
                                    initGuest(global_id);
                                }
                            }
                        })
                        $('.swal2-title').addClass('justify-content-center')
                    }else{
                        let attr_id = $(this).attr('id');
                        let attr_data = $(this).attr('data');

                        Swal.fire({
                            title: 'Delete Guest?',
                            text: "Deleted Guest will be permanently lost!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#7F16A7',
                            confirmButtonText: 'Yes, Delete',
                        }).then(function (result) {
                            if (result.value) {
                                $.ajax({
                                    url: '{{url('admin/master/courses/master-lesson/guest-star')}}/'+attr_id,
                                    type: 'DELETE',
                                    dataType: 'json',
                                })
                                .done(function(res, xhr, meta) {
                                    initDataGuest(attr_data)
                                })
                                .fail(function(res, error) {
                                    toastr.error(res.responseJSON.message, 'Failed')
                                })
                                .always(function() { });
                            }
                        })
                        $('.swal2-title').addClass('justify-content-center')
                    }
                })

                $(document).on('change', '#classroom-category', function(){
                    if($('#classroom-category').val() == ""){
                        $('.required-classroom-category').show();
                        $('.select-sub-category').hide();
                        $('.parent-sub-category').removeClass('col-md-6');
                    }else{
                        $('.required-classroom-category').hide();
                        get_sub_classroom_category($('#classroom-category').val())
                    }
                })

                $(document).on('click', '#btn-add-guest', function(){
                    if($('#guest-star').val() != ""){
                        const index = arr_guest.indexOf($('#guest-star').val());

                        if (index == -1) {
                            arr_guest.push($('#guest-star').val())
                            arr_guest_name.push($('#guest-star option:selected').text())
                        }

                        $('#guest-star').val('')
                        if(global_id){
                            initDataGuest(global_id);
                        }else{
                            initGuest()
                        }
                    }
                })
            },
            initGuest = (id) => {
                if(id){
                    initDataGuest(id)
                }else{
                    let element = '';
                    $.each(arr_guest, function(index, data){
                        element += `<tr>
                                <td class="text-center">
                                    <i id="-" data="${data}" class="far fa-trash-alt icn text-danger btn-delete-guest"></i>
                                </td>
                                <td>${arr_guest_name[index]}</td>
                            </tr>`;
                    })

                    $('#guest-tbody').html(element);
                }
            },
            initDataGuest = (id) => {
                $.ajax({
                    url: '{{url('admin/master/courses/master-lesson/guest-star')}}/'+id,
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = '';
                    $.each(res.data, function(index, data){
                        element += `<tr>
                                <td class="text-center">
                                    <i id="${data.id}" data="${data.master_lesson_id}" class="far fa-trash-alt icn text-danger btn-delete-guest"></i>
                                </td>
                                <td>${data.name}</td>
                            </tr>`;
                    })

                    $.each(arr_guest, function(index, data){
                        element += `<tr>
                                <td class="text-center">
                                    <i id="-" data="${data}" class="far fa-trash-alt icn text-danger btn-delete-guest"></i>
                                </td>
                                <td>${arr_guest_name[index]}</td>
                            </tr>`;
                    })

                    $('#guest-tbody').html(element);
                })
            },
            formSubmit = () => {
                $('#form-master-lesson').submit(function(event){
                    event.preventDefault();

                    var validate = ss_validate([
                        'classroom-category',
                        'sub-classroom-category',
                        'platform',
                    ]);

                    if(!validate){
                        return false;
                    }

                    let form_data = new FormData(this)

                    if(arr_path.length > 0){
                        form_data.append('file', arr_path[0]['path']);
                    }

                    for (var i = 0; i < arr_guest.length; i++) {
                        form_data.append('guests[]', arr_guest[i]);
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
                        hideModal('modal-master-lesson');
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
                    url: '{{url('public/get-classroom-category')}}',
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
                    if(res.data.length > 0){
                        $('.select-sub-category').show();

                        let element = `<option value="">Select Sub Class Category</option>`

                        $.each(res.data, function(index, data) {
                            if(select_id == data.id){
                                element += `<option value="${data.id}" selected>${data.name}</option>`;
                            }else{
                                element += `<option value="${data.id}">${data.name}</option>`;
                            }
                        });

                        $('#sub-classroom-category').html(element);
                        $('.parent-sub-category').addClass('col-md-6');
                    }else{
                        $('.select-sub-category').hide();
                        $('.parent-sub-category').removeClass('col-md-6');
                    }
                })
            },
            get_platform = (select_id) => {
                if(init_platform){
                    init_platform.destroy();
                }

                init_platform = new SlimSelect({
                    select: '#platform'
                })

                $.ajax({
                    url: '{{url('public/get-platform')}}',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Select Media</option>`

                    $.each(res.data, function(index, data) {
                        if(select_id == data.id){
                            element += `<option value="${data.id}" selected>${data.name}</option>`;
                        }else{
                            element += `<option value="${data.id}">${data.name}</option>`;
                        }
                    });

                    $('#platform').html(element);
                })
            },
            get_guest_star = (select_id) => {
                if(init_guest_star){
                    init_guest_star.destroy();
                }

                init_guest_star = new SlimSelect({
                    select: '#guest-star'
                })

                $.ajax({
                    url: '{{url('public/get-guest-star')}}',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Select Guest Star</option>`

                    $.each(res.data, function(index, data) {
                        if(select_id == data.id){
                            element += `<option value="${data.id}" selected>${data.name}</option>`;
                        }else{
                            element += `<option value="${data.id}">${data.name}</option>`;
                        }
                    });

                    $('#guest-star').html(element);
                })
            }

            const initDropzone = () => {
                docsDropzone = new Dropzone( "#kt_dropzone_2", {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{url('media/file')}}",
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
                    success: function (file, response) {
                        arr_path.push({id: file.upload.uuid, path_id: response.data.id, path: response.data.path})
                        $('.dz-remove').addClass('dz-style-custom');
                    },
                    removedfile: function (file) {
                        $.each(arr_path, function(index, arr_data){
                            if(file.upload.uuid == arr_data['id']){
                                arr_path.splice(index, 1);

                                $.ajax({
                                    url: "{{url('media/file')}}/"+arr_data['path_id'],
                                    type: 'DELETE',
                                    dataType: 'json',
                                })
                                .done(function(res, xhr, meta) {

                                })
                                .fail(function(res, error) {
                                    toastr.error(res.responseJSON.message, 'Failed')
                                })
                            }
                        })

                        var _ref;
                        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                    },
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
