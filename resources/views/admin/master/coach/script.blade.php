<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, element_sosmed, option = new Array;

            $(document).ready(function() {
                initTable();
                formSubmit();
                initAction();
                getSosmed();
                // $('.dropify').dropify();
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/master/coach/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'name' },
                        { defaultContent: '' },
                        { data: 'expertise' },
                        { defaultContent: '' },
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
                            targets: 2,
                            searchable: false,
                            orderable: true,
                            className: "text-center",
                            render : function(type, full, meta) {
                                return `0`;
                            }
                        },
                        {
                            targets: 4,
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                            render : function(data, type, full, meta) {
                                return `
                                <div class="d-flex justify-content-center">
                                    <span class="switch switch-sm switch-outline switch-icon switch-success">
                                        <label>
                                        <input type="checkbox" checked="checked" name="select"/>
                                        <span></span>
                                        </label>
                                    </span>
                                </div>
                                `
                            }
                        },
                        {
                            targets: 1,
                            data: "name",
                            render : function(data, type, full, meta) {
                                // let package_type;

                                // if(full.package_type == 2){
                                //     package_type = 'Reguler';
                                // }else{
                                //     package_type = 'Special';
                                // }

                                return `
                                        <div class="d-flex align-items-center">
                                            <div class="mr-5">
                                                <img src="${full.image_url}" class="rounded" width="50" height="50"/>
                                            </div>
                                            <div class="d-flex flex-column font-weight-bold">
                                                <span>${data}</span>
                                            </div>
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
                                    <a href="{{url('/admin/master/coach/update')}}/${data}" title="Edit" class="btn btn-edit btn-sm btn-clean btn-icon mr-2" title="Edit details">
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
                                    <a href="{{url('/admin/master/coach')}}/${data}" title="Delete" class="btn btn-delete btn-sm btn-clean btn-icon" title="Delete">
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

                    $('#form-coach').trigger("reset");
                    $('#form-coach').attr('action','{{url('admin/master/coach')}}');
                    $('#form-coach').attr('method','POST');
                    $('#form-coach').attr('data-form','insert');
                    $('#form-coach').find('input[name="profile_avatar"]').attr('required',true);

                    $('.password-setting').html(`
                        <div class="row mb-5">
                            <div class="col-12">
                                <label>Password<span class="text-danger">*</span></label>
                                <div class="password">
                                    <input type="password" name="password" class="form-control" required id="password" placeholder="Password">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>Konfirmasi Password<span class="text-danger">*</span></label>
                                <div class="password-confirm">
                                    <input type="password" name="password_confirmation" class="form-control" required id="password-confirm" placeholder="Konfirmasi Password">
                                </div>
                            </div>
                        </div>
                    `);
                    $('.password-setting').addClass('col-6');

                    showModal('modal-coach');
                });

                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-coach').trigger("reset");
                    $('#form-coach').attr('action', $(this).attr('href'));
                    $('#form-coach').attr('method','POST');
                    $('#form-coach').attr('data-form','update');

                    $('#form-coach').find('input[name="name"]').val(data.name);
                    $('#form-coach').find('input[name="username"]').val(data.username);
                    $('#form-coach').find('input[name="email"]').val(data.email);
                    $('#form-coach').find('input[name="expertise"]').val(data.expertise);
                    $('#form-coach').find('textarea[name="profil_description"]').val(data.description);
                    $('#form-coach').find('input[name="phone"]').val(data.phone);
                    $('#form-coach').find('input[name="profile_avatar"]').attr('required',false);

                    $('.img-profile-edit').attr('src',`${data.image_url}`);
                    // $('.password-setting').html(`
                    //     <div class="row mb-5">
                    //         <div class="col-12">
                    //             <label>Password<span class="text-danger">*</span></label>
                    //             <div class="password">
                    //                 <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    //             </div>
                    //         </div>
                    //     </div>
                    //     <div class="row">
                    //         <div class="col-12">
                    //             <label>Konfirmasi Password<span class="text-danger">*</span></label>
                    //             <div class="password-confirm">
                    //                 <input type="password" name="password_confirmation" class="form-control" id="password-confirm" placeholder="Konfirmasi Password">
                    //             </div>
                    //         </div>
                    //     </div>
                    // `);

                    getCoachSosmed(data.id);
                    $('.password-setting').removeClass('col-6');
                    $('.password-setting').empty();

                    showModal('modal-coach');
                });

                $(document).on('click', '.btn-delete', function(event){
                    event.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Delete Classroom Category?',
                        text: "Deleted Classroom Category will be permanently lost!",
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

                $('.upload').on('change', function() {
                    console.log('ua');
                    let sizeFile = $(this)[0].files[0].size/1024/1024;
                    sizeFile = sizeFile.toFixed(2);
                    if(sizeFile > 2){
                        toastr.error('Maksimal Upload: 2 MB', 'Failed')
                    }
                    readURL(this);
                });

                // $('.tools').autocomplete({
                //     minLength: 1,
                //     source: function(request, response) {
                //         $.ajax({
                //             url: "{{ url('admin/master/courses/classroom/tools/ac') }}",
                //             type: 'GET',
                //             dataType: 'json',
                //             data: {
                //                 param : request.term,
                //             },
                //         })
                //         .done(function(res) {
                //             response(res.data);
                //         })
                //     },
                //     focus: function( event, ui ) {
                //         return false;
                //     },
                //     select: function( event, ui ) {
                //         $('.tools').val(ui.item.text);

                //         return false;
                //     }
                // })
                // .autocomplete('instance')._renderItem = function(ul, item) {
                //     var template = `
                //         <div class="pb-0">
                //             <span class="pb-0">${item.text}</span></span>
                //         </div>
                //     `
                //     return $('<li>').append(template).appendTo(ul);
                // };

                $(document).on('click','.btn-add-medsos',function(event){
                    event.preventDefault();
                    $('.medsos').append(element_sosmed);
                })

                $(document).on('click','.delete-medsos',function(event){
                    event.preventDefault();
                    let element = $(this);
                    let url = $(this).data('url');

                    if( url == "" || url == null){
                        Swal.fire({
                            title: 'Delete Coach?',
                            text: "Deleted Coach will be permanently lost!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#7F16A7',
                            confirmButtonText: 'Yes, Delete',
                        }).then(function (result) {
                            if (result.value) {
                                element.parent().parent().remove();
                            }
                        })

                        $('.swal2-title').addClass('justify-content-center')
                    }else{
                        Swal.fire({
                            title: 'Delete Media Social?',
                            text: "Deleted Media Social will be permanently lost!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#7F16A7',
                            confirmButtonText: 'Yes, Delete',
                        }).then(function (result) {
                            if (result.value) {
                                element.parent().parent().remove();
                                $.ajax({
                                    url: `${url}`,
                                    type: 'DELETE',
                                })
                                .done(function(res, xhr, meta) {

                                })
                                .fail(function(res, error) {
                                    if (res.status == 400 || res.status == 422) {
                                        $.each(res.responseJSON.errors, function(index, err) {
                                            if (Array.isArray(err)) {
                                                $.each(err, function(index, val) {
                                                    toastr.error(val, 'Failed')
                                                });
                                            }
                                            else {
                                                toastr.error(err, 'Failed')
                                            }
                                        });
                                    }
                                    else {
                                        toastr.error(res.responseJSON.message, 'Failed')
                                    }
                                })
                                .always(function() {
                                    btn_loading('stop')
                                });
                            }
                        })

                        $('.swal2-title').addClass('justify-content-center')
                    }
                })
            },
            getCoachSosmed = (coach_id) => {
                $.ajax({
                    url: `{{ url('admin/master/coach/coach-sosmed') }}/${coach_id}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {

                    let element = ``;

                    $.each(res.data, function(index, data) {
                        element += `
                        <div class="list-sosmed mb-3 mt-3">
                            <div class="row">
                                <div class="col-11 text-right">
                                    <input type="hidden" name="init_sosmed_coach[]" class="init_sosmed_coach" value="${data.id}">
                                    <div class="input-group">
                                        <input type="text" name="url_sosmed[]" class="form-control" required value="${data.url}">
                                        <select name="sosmed[]" class="form-control" required>
                                            <option value="">Pilih Sosmed</option>
                        `;

                        $.each(option, function(index, value) {
                            if(data.sosmed_id==value[0]){
                                element +=`<option value="${value[0]}" selected>${value[1]}</option>`;
                            }
                            else{
                                element +=`<option value="${value[0]}">${value[1]}</option>`;
                            }
                        });
                        element += `
                                    </div>
                                </div>
                                <div class="col-1">
                                    <a href="javascript:void(0)" class="delete-medsos" data-url="{{ url('admin/master/coach/delete-medsos') }}/${data.id}">
                                        <span class="svg-icon svg-icon-danger svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>
                                                <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        `;

                    });
                    console.log(element);
                    $('#medsos').html(element);
                })
                .fail(function(res, error) {
                    if (res.status == 400 || res.status == 422) {
                        $.each(res.responseJSON.errors, function(index, err) {
                            if (Array.isArray(err)) {
                                $.each(err, function(index, val) {
                                    toastr.error(val, 'Failed')
                                });
                            }
                            else {
                                toastr.error(err, 'Failed')
                            }
                        });
                    }
                    else {
                        toastr.error(res.responseJSON.message, 'Failed')
                    }
                })
                .always(function() {
                    btn_loading('stop')
                });
            },
            formSubmit = () => {
                $('#form-coach').submit(function(event){
                    event.preventDefault();
                    let avatar = $('.upload').val();
                    let form = $(this).data('form');
                    if(form == 'insert'){
                        if(avatar == null || avatar == ''){
                            return toastr.error('Avatar harus diisi', 'Failed')
                        }
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
                        // if(res.status == 200){
                            toastr.success(res.message, 'Success');
                            init_table.draw(false);
                            hideModal('modal-coach');
                        // }
                    })
                    .fail(function(res, error) {
                        if (res.status == 400 || res.status == 422) {
                            $.each(res.responseJSON.errors, function(index, err) {
                                if (Array.isArray(err)) {
                                    $.each(err, function(index, val) {
                                        toastr.error(val, 'Failed')
                                    });
                                }
                                else {
                                    toastr.error(err, 'Failed')
                                }
                            });
                        }
                        else {
                            toastr.error(res.responseJSON.message, 'Failed')
                        }
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
            },
            getSosmed = () => {
                $.ajax({
                    url: `{{ url('public/get-sosmed') }}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    element_sosmed = `
                    <div class="list-sosmed mb-3">
                        <div class="row">
                            <div class="col-11 text-right">
                                <a href="javascript:void(0)" class="delete-medsos" data-url="">
                                    <span class="svg-icon svg-icon-danger svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>
                                            <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                        </g>
                                    </svg><!--end::Svg Icon--></span>
                                </a>
                                <input type="hidden" name="init_sosmed_coach[]" class="init_sosmed_coach">
                                <div class="input-group">
                                    <input type="text" name="url_sosmed[]" class="form-control" required>
                                    <select name="sosmed[]" class="form-control" required>
                                        <option value="">Pilih Sosmed</option>
                    `;
                    $.each(res.data, function(index, data) {
                        element_sosmed += `<option value="${data.id}">${data.name}</option>`;
                        option[index] = [
                            data.id,
                            data.name
                        ];
                    });

                    element_sosmed += `
                                </div>
                            </div>
                        </div>
                    </div>`;
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

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
            get_sub_classroom_category = (id) => {
                if(init_sub_classroom_category){
                    init_sub_classroom_category.destroy();
                }

                init_sub_classroom_category = new SlimSelect({
                    select: '#sub-classroom-category'
                })

                $.ajax({
                    url: '{{url('public/get-sub-classroom-category')}}/'+category_id,
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Select Sub Class Category</option>`
                    $.each(res.data, function(index, data) {
                        if(id == data.id){
                            element += `<option value="${data.id}" selected>${data.name}</option>`;
                        }else{
                            element += `<option value="${data.id}">${data.name}</option>`;
                        }
                    });

                    $('#sub-classroom-category').html(element);
                })
            },
            get_sub_classroom_category_by_category = (id, select_id) => {
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
                    }else{
                        $('.select-sub-category').hide();
                    }
                })
            }
        };

        const readURL = (input) => {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {

                    $('.image').html(`
                        <img src="${e.target.result}" class="img-profile-edit rounded" style="width:194px !important; height:194px !important;">
                    `);
                };
                reader.readAsDataURL(input.files[0]);
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
