<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, element_sosmed, option = new Array;

            $(document).ready(function() {
                initTable();
                formSubmit();
                initAction();
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
                        url: "{{ url('admin/master/student/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'name' },
                        { data: 'email' },
                        { data: 'phone' },
                        { data: 'jumlah_class' },
                        { data: 'expertise' },
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
                                                <span>${data}</span>
                                            </div>
                                        </div>
                                `;
                            }
                        },
                        {
                            targets: 2,
                            searchable: false,
                            orderable: true,
                            className: "text-center",
                        },
                        {
                            targets: 3,
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                        },
                        {
                            targets: 4,
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                        },
                        {
                            targets: 5,
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                        },
                        {
                            targets: -1,
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                            data: "id",
                            render : function(data, type, full, meta) {
                                return `
                                    <a href="{{url('/admin/master/student/update')}}/${data}" title="Edit" class="btn btn-edit btn-sm btn-clean btn-icon mr-2" title="Edit details">
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
                                    <a href="{{url('/admin/master/student')}}/${data}" title="Delete" class="btn btn-delete btn-sm btn-clean btn-icon" title="Delete">
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

                    $('#form-student').trigger("reset");
                    $('#form-student').attr('action','{{url('admin/master/student')}}');
                    $('#form-student').attr('method','POST');
                    $('#form-student').attr('data-form','insert');
                    $('#form-student').find('input[name="profile_avatar"]').attr('required',true);

                    $('.password-setting').html(`
                        <div class="row mb-5">
                            <div class="col-6">
                                <label>Password<span class="text-danger">*</span></label>
                                <div class="password">
                                    <input type="password" name="password" class="form-control" required id="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="col-6">
                                <label>Konfirmasi Password<span class="text-danger">*</span></label>
                                <div class="password-confirm">
                                    <input type="password" name="password_confirmation" class="form-control" required id="password-confirm" placeholder="Konfirmasi Password">
                                </div>
                            </div>
                        </div>
                    `);
                    $('.password-setting').addClass('col-6');

                    showModal('modal-student');
                });

                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-student').trigger("reset");
                    $('#form-student').attr('action', $(this).attr('href'));
                    $('#form-student').attr('method','POST');
                    $('#form-student').attr('data-form','update');

                    $('#form-student').find('input[name="name"]').val(data.name);
                    $('#form-student').find('input[name="username"]').val(data.username);
                    $('#form-student').find('input[name="email"]').val(data.email);
                    $('#form-student').find('input[name="expertise"]').val(data.expertise);
                    $('#form-student').find('textarea[name="profil_description"]').val(data.description);
                    $('#form-student').find('input[name="phone"]').val(data.phone);
                    $('#form-student').find('input[name="profile_avatar"]').attr('required',false);

                    $('.img-profile-edit').attr('src',`${data.image_url}`);

                    $('.password-setting').removeClass('col-6');
                    $('.password-setting').empty();

                    showModal('modal-student');
                });

                $(document).on('click', '.btn-delete', function(event){
                    event.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Delete Student?',
                        text: "Deleted Student will be permanently lost!",
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

                $(document).on('click','.btn-add-medsos',function(event){
                    event.preventDefault();
                    $('#medsos').append(element_sosmed);
                })

                $(document).on('click','.delete-medsos',function(event){
                    event.preventDefault();
                    let element = $(this);
                    let url = $(this).data('url');

                    if( url == "" || url == null){
                        Swal.fire({
                            title: 'Delete student?',
                            text: "Deleted student will be permanently lost!",
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
                });

                $(document).on('click', '.btn-permission', function(event){
                    event.preventDefault();

                    $('#form_permission').attr('action', $(this).attr('href'));
                    showModal('modal_permission');

                    $.ui.fancytree.getTree("#tree-table").visit(function(node){
                        if(node.expanded == true && node.children != null){
                            node.toggleExpanded();
                        }
                    });

                    $('input[type=checkbox]').prop('checked', false);

                    setChecked($(this).attr('href'));
                });
            },
            formSubmit = () => {
                $('#form-student').submit(function(event){
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
                            hideModal('modal-student');
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

                $('#form_permission').submit(function(event){
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
                        url: '{{ asset('data/student.json') }}'
                    },
                    lazyLoad: function(event, data) {
                        data.result = {url: '{{ asset('data/student.json') }}'}
                    },
                    renderColumns: function(event, data) {
                        var node = data.node;

                        $tdList = $(node.tr).find('>td');

                        $tdList.eq(0).addClass('text-left');
                        $tdList.eq(1).addClass('text-center').html(`<input type="checkbox" class="form-input-styled permissions ${node.key}" data-parent="${node.parent.key}" name="permissions[]" value="${node.key}" id="${node.key}">`);
                        if (node.data.crud) {
                            $tdList.eq(2).addClass(`text-center`).html(`<input type="checkbox" class="form-input-styled permissions ${node.key}_crud" name="permissions[]" value="${node.key}_list" data-parent="${node.key}">`);
                            $tdList.eq(3).addClass(`text-center`).html(`<input type="checkbox" class="form-input-styled permissions ${node.key}_crud" name="permissions[]" value="${node.key}_insert" data-parent="${node.key}">`);
                            $tdList.eq(4).addClass(`text-center`).html(`<input type="checkbox" class="form-input-styled permissions ${node.key}_crud" name="permissions[]" value="${node.key}_update" data-parent="${node.key}">`);
                            $tdList.eq(5).addClass(`text-center`).html(`<input type="checkbox" class="form-input-styled permissions ${node.key}_crud" name="permissions[]" value="${node.key}_delete" data-parent="${node.key}">`);
                            $tdList.eq(6).addClass(`text-center`).html(`<input type="checkbox" class="form-input-styled permissions ${node.key}_crud" name="permissions[]" value="${node.key}_print" data-parent="${node.key}">`);
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
                        if($input.is(':checked')){
                            var val = $input.val();
                            $('#tree-table').find(`[data-parent="${val}"]`).prop('checked', true).trigger('change');
                        }
                        else{
                            var val = $input.val();
                            $('#tree-table').find(`[data-parent="${val}"]`).prop('checked', false).trigger('change');
                        }
                    }
                    else {
                        if($input.is(':checked')){
                            var val = $input.val();
                            $('#tree-table').find(`[data-parent="${val}"]`).prop('checked', true);
                        }
                        else{
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

                            if($input.is(':checked')){
                                $('#tree-table').find(`.${parent}`).prop('checked', true);
                            }
                            else{
                                if ($('#tree-table').find(`[data-parent="${parent}"]:checked`).length <= 0) {
                                    $('#tree-table').find(`.${parent}`).prop('checked', false);
                                }
                            }

                            if (typeof parent_parent != 'undefined') {
                                if (parent_parent != 'root_1') {
                                    if($('#tree-table').find(`[data-parent="${parent_parent}"]:checked`).length > 0){
                                        $('#tree-table').find(`.${parent_parent}`).prop('checked', true);
                                    }
                                    else{
                                        if ($('#tree-table').find(`[data-parent="${parent_parent}"]:checked`).length <= 0) {
                                            $('#tree-table').find(`.${parent_parent}`).prop('checked', false);
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
                            $(val).prop("checked","true");
                        });
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Gagal')
                })
                .always(function() { });
            }

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
