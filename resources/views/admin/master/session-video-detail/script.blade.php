<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table,
                file_init_table,
                init_classroom_category,
                init_sub_classroom_category,
                init_coach,
                init_expertise;
            var arr_path = [],
                file_arr_path = [],
                docsDropzone,
                fileDocsDropzone;


            $(document).ready(function() {
                initTable();
                formSubmit();
                initAction();
                initDropzone();
                initFileDropzone();
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 550,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/master/courses/session-video/detail/dt/'.$data->id) }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'name' },
                        { data: 'is_converter_complete' },
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
                            targets: -2,
                            className: "text-center",
                            data: "is_converter_complete",
                            render : function(data, type, full, meta) {
                                if(full.is_youtube)
                                    return `<span class="label label-lg label-light-info label-inline font-weight-bold py-4">Ready</span>`

                                return data  ?
                                    `<span class="label label-lg label-light-info label-inline font-weight-bold py-4">Ready</span>`:
                                    `<span class="label label-lg label-light-warning label-inline font-weight-bold py-4">Pending</span>`
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
                                    <a href="{{url('/admin/master/courses/session-video/detail/update')}}/${data}" title="Edit" class="btn btn-edit btn-sm btn-clean btn-icon mr-2" title="Edit details">
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
                                    <a href="{{url('/admin/master/courses/session-video/detail')}}/${data}" title="Delete" class="btn btn-delete btn-sm btn-clean btn-icon" title="Delete">
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

                file_init_table = $('#file-init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 550,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/master/courses/session-video/file/dt/'.$data->id) }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'name' },
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
                            targets: -1,
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                            data: "id",
                            render : function(data, type, full, meta) {
                                return `
                                    <a href="{{url('/admin/master/courses/session-video/file/update')}}/${data}" title="Edit" class="btn file-btn-edit btn-sm btn-clean btn-icon mr-2" title="Edit">
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
                                    <a href="{{url('/admin/master/courses/session-video/file')}}/${data}" title="Delete" class="btn file-btn-delete btn-sm btn-clean btn-icon" title="Delete">
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

                $('#file-search').keyup(searchDelay(function(event) {
                    file_init_table.search($(this).val()).draw()
                }, 1000));

                $('#file-pageLength').on('change', function () {
                    file_init_table.page.len(this.value).draw();
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

                    $('#form-session-video-detail').trigger("reset");
                    $('#form-session-video-detail').attr('action','{{url('admin/master/courses/session-video/detail/store')}}');
                    $('#form-session-video-detail').attr('method','POST');

                    $('#switch-youtube').attr('checked', true);
                    $('#i-url').prop('required', true);
                    $('.file-upload').hide()
                    $('.url-input').show()

                    docsDropzone.removeAllFiles( true );

                    showModal('modal-session-video-detail');
                });

                $(document).on('click', '#file-add-btn', function(event){
                    event.preventDefault();

                    $('#form-session-video-file').trigger("reset");
                    $('#form-session-video-file').attr('action','{{url('admin/master/courses/session-video/file/store')}}');
                    $('#form-session-video-file').attr('method','POST');

                    fileDocsDropzone.removeAllFiles( true );

                    showModal('modal-session-video-file');
                });

                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-session-video-detail').trigger("reset");
                    $('#form-session-video-detail').attr('action', $(this).attr('href'));
                    $('#form-session-video-detail').attr('method','POST');

                    $('#form-session-video-detail').find('input[name="name"]').val(data.name);

                    if(data.is_public){
                        $('#switch-public').attr('checked', true);
                    }

                    if(data.is_youtube){
                        $('#form-session-video-detail').find('input[name="url"]').val(data.url);
                        $('#switch-youtube').attr('checked', true);
                        $('#i-url').prop('required', true);
                        $('.file-upload').hide()
                        $('.url-input').show()
                    }else{
                        $('#switch-youtube').attr('checked', false);
                        $('#i-url').prop('required', false);
                        $('.file-upload').show()
                        $('.url-input').hide()

                        docsDropzone.removeAllFiles( true );

                        var mockFile = {
                            name: data.url,
                            accepted: true
                        };

                        docsDropzone.files.push(mockFile);
                        docsDropzone.emit("addedfile", mockFile);
                        docsDropzone.emit("thumbnail", mockFile, data.file_url);
                        docsDropzone.emit("complete", mockFile);
                    }

                    showModal('modal-session-video-detail');
                });

                $(document).on('click', '.file-btn-edit', function(event){
                    event.preventDefault();

                    var data = file_init_table.row($(this).parents('tr')).data();

                    $('#form-session-video-file').trigger("reset");
                    $('#form-session-video-file').attr('action', $(this).attr('href'));
                    $('#form-session-video-file').attr('method','POST');

                    $('#form-session-video-file').find('input[name="name"]').val(data.name);

                        docsDropzone.removeAllFiles( true );

                        var mockFile = {
                            name: data.url,
                            accepted: true
                        };

                        docsDropzone.files.push(mockFile);
                        docsDropzone.emit("addedfile", mockFile);
                        docsDropzone.emit("thumbnail", mockFile, data.file_url);
                        docsDropzone.emit("complete", mockFile);

                    showModal('modal-session-video-detail');
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

                $(document).on('click', '.file-btn-delete', function(event){
                    event.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Delete File?',
                        text: "Deleted File will be permanently lost!",
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
                                file_init_table.draw(false);
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() { });
                        }
                    })
                    $('.swal2-title').addClass('justify-content-center')
                });

                $(document).on('change', '#switch-youtube', function(){
                    if($('input[name="is_youtube"]:checked').length == 1){
                        $('.file-upload').hide()
                        $('.url-input').show()
                        $('#i-url').prop('required', true);
                    }else{
                        $('.file-upload').show()
                        $('.url-input').hide()
                        $('#i-url').prop('required', false);

                        docsDropzone.removeAllFiles( true );
                    }
                })
            },
            formSubmit = () => {
                $('#form-session-video-detail').submit(function(event){
                    event.preventDefault();

                    let form_data = new FormData(this)

                    if(arr_path.length > 0){
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
                        hideModal('modal-session-video-detail');
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });

                $('#form-session-video-file').submit(function(event){
                    event.preventDefault();

                    let form_data = new FormData(this)

                    if(file_arr_path.length > 0){
                        form_data.append('file', file_arr_path[0]['path']);
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
                        file_init_table.draw(false);
                        file_arr_path = [];
                        hideModal('modal-session-video-file');
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
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
            },
            initFileDropzone = () => {
                fileDocsDropzone = new Dropzone( "#kt_dropzone_3", {
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
                        file_arr_path.push({id: file.upload.uuid, path_id: response.data.id, path: response.data.path})
                        $('.dz-remove').addClass('dz-style-custom');
                    },
                    removedfile: function (file) {
                        $.each(file_arr_path, function(index, file_arr_data){
                            if(file.upload.uuid == file_arr_data['id']){
                                file_arr_path.splice(index, 1);

                                $.ajax({
                                    url: "{{url('media/file')}}/"+file_arr_data['path_id'],
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
