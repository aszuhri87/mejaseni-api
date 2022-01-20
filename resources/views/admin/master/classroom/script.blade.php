<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, init_classroom_category, init_sub_classroom_category, global_id;
            var arr_tools = [];

            $(document).ready(function() {
                initTable();
                formSubmit();
                initAction();
                $('.dropify').dropify();
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/master/courses/classroom/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'name' },
                        { data: 'category' },
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
                                let package_type;

                                if(full.package_type == 2){
                                    package_type = 'Reguler';
                                }else{
                                    package_type = 'Special';
                                }

                                return `
                                        <div class="d-flex align-items-center">
                                            <div class="mr-5">
                                                <img src="${full.image_url}" class="rounded" width="50" height="50"/>
                                            </div>
                                            <div class="d-flex flex-column font-weight-bold">
                                                <p class="mb-1 font-size-lg">${data}</p>
                                                <span class="text-muted">${package_type} Package</span>
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
                            data: "price",
                            render: function(data, type, full, meta) {
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">Rp ${numeral(data).format('0,0')}</p>
                                        <span class="text-muted">${full.session_total} Sesi</span>
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
                                    @can('class_update')
                                    <a href="{{url('/admin/master/courses/classroom/update')}}/${data}" title="Edit" class="btn btn-edit btn-sm btn-clean btn-icon mr-2" title="Edit details">
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
                                    @can('class_delete')
                                    <a href="{{url('/admin/master/courses/classroom')}}/${data}" title="Delete" class="btn btn-delete btn-sm btn-clean btn-icon" title="Delete">
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

                    $('#form-classroom').trigger("reset");
                    $('#form-classroom').attr('action','{{url('admin/master/courses/classroom')}}');
                    $('#form-classroom').attr('method','POST');

                    get_classroom_category();
                    $('.switch-sub-package').hide();
                    $('.select-sub-category').hide();

                    $("input[name=package_type]").attr('checked', false);
                    $("input[name=sub_package_type]").attr('checked', false);
                    $('#switch-sub-package').attr('checked', false);
                    $('#sub-classroom-category').val('');
                    $('#classroom-category').val('');

                    arr_tools = [];
                    initTools(arr_tools);

                    $('#image').html('<input type="file" name="image" class="dropify image"/>');

                    $('.dropify').dropify();

                    showModal('modal-classroom');
                });

                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();
                    global_id = data.id;

                    $('#form-classroom').trigger("reset");
                    $('#form-classroom').attr('action', $(this).attr('href'));
                    $('#form-classroom').attr('method','POST');

                    $("input[name=package_type]").attr('checked', false);
                    $("input[name=sub_package_type]").attr('checked', false);
                    $('#switch-sub-package').attr('checked', false);
                    $('#switch-buy-btn-disable').attr('checked', false);
                    $('#switch-hide').attr('checked', false);
                    $('#sub-classroom-category').val('');
                    $('#classroom-category').val('');

                    $('#form-classroom').find('input[name="name"]').val(data.name);
                    $('#form-classroom').find('input[name="session"]').val(data.session_total);
                    $('#form-classroom').find('input[name="duration"]').val(data.session_duration);
                    $('#form-classroom').find('input[name="price"]').val(data.price);
                    $('#form-classroom').find('textarea[name="description"]').val(data.description);

                    get_classroom_category(data.classroom_category_id)

                    if(data.sub_classroom_category_id){
                        get_sub_classroom_category_by_category(data.classroom_category_id, data.sub_classroom_category_id)
                    }else{
                        $('.select-sub-category').hide();
                    }

                    if(data.package_type == 2){
                        $("input[name=package_type][value=" + data.package_type + "]").attr('checked', 'checked');
                        $('.switch-sub-package').show();

                        if(data.sub_package_type){
                            $('.select-sub-package').show();
                            $('#switch-sub-package').attr('checked', 'checked');
                            $("input[name=sub_package_type][value=" + data.sub_package_type + "]").attr('checked', 'checked');
                        }else{
                            $('.select-sub-package').hide();
                        }
                    }else{
                        $("input[name=package_type][value=" + data.package_type + "]").attr('checked', 'checked');
                        $('.switch-sub-package').hide();
                    }

                    if(data.buy_btn_disable){
                        $('#switch-buy-btn-disable').attr('checked', 'checked');
                    }

                    if(data.hide){
                        $('#switch-hide').attr('checked', 'checked');
                    }

                    $('#image').empty();

                    if(data.image_url){
                        element = `<input type="file" name="image" class="dropify image"  data-default-file="${data.image_url}"/>`;
                    }else{
                        element = `<input type="file" name="image" class="dropify image"/>`;
                    }

                    $('#image').html(element);

                    $('.dropify').dropify();

                    arr_tools = [];
                    initDataTools(global_id);

                    showModal('modal-classroom');
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

                $(document).on('change', '.radio-package', function(){
                    if($(this).val() == 2){
                        $('.switch-sub-package').show();
                    }else{
                        $('.switch-sub-package').hide();
                        $('#form-classroom').find('input[name="name"]').val('');
                    }
                })

                $(document).on('change', '#switch-sub-package', function(){
                    if($('input[name="switch_sub"]:checked').length == 1){
                        $('.select-sub-package').show();
                    }else{
                        $('.select-sub-package').hide();
                    }
                })

                $(document).on('change', '.sub-package-type', function(){
                    if($('input[name="switch_sub"]:checked').length == 1){
                        let category = $("#classroom-category option:selected" ).text(),
                            sub_category = $("#sub-classroom-category option:selected" ).text(),
                            val_sub_category = $("#sub-classroom-category" ).val(),
                            name;

                        if(!!val_sub_category && val_sub_category != ''){
                            if($(this).val() == 1){
                                name = sub_category + ' Basic'
                            }else if($(this).val() == 2){
                                name = sub_category + ' Intermediate'
                            }else{
                                name = sub_category + ' Advanced'
                            }
                        }else{
                            if($(this).val() == 1){
                                name = category + ' Basic'
                            }else if($(this).val() == 2){
                                name = category + ' Intermediate'
                            }else{
                                name = category + ' Advanced'
                            }
                        }

                        $('#form-classroom').find('input[name="name"]').val(name);
                    }else{
                        $('#form-classroom').find('input[name="name"]').val('');
                    }
                })

                $(document).on('change', '#classroom-category', function(){
                    if($('#classroom-category').val() == ""){
                        $('.required-classroom-category').show();
                        $('.select-sub-category').hide();
                    }else{
                        $('.required-classroom-category').hide();
                        get_sub_classroom_category_by_category($('#classroom-category').val())
                    }
                })

                $('.tools').autocomplete({
                    minLength: 1,
                    source: function(request, response) {
                        $.ajax({
                            url: "{{ url('admin/master/courses/classroom/tools/ac') }}",
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                param : request.term,
                            },
                        })
                        .done(function(res) {
                            response(res.data);
                        })
                    },
                    focus: function( event, ui ) {
                        return false;
                    },
                    select: function( event, ui ) {
                        $('.tools').val(ui.item.text);

                        return false;
                    }
                })
                .autocomplete('instance')._renderItem = function(ul, item) {
                    var template = `
                        <div class="pb-0">
                            <span class="pb-0">${item.text}</span></span>
                        </div>
                    `
                    return $('<li>').append(template).appendTo(ul);
                };

                $(document).on('click','#btn-add-tools',function(){
                    if($('.tools').val() != ""){
                        const index = arr_tools.indexOf($('.tools').val());

                        if (index == -1) {
                            arr_tools.push($('.tools').val())
                            initTools(arr_tools, global_id);
                        }

                        $('.tools').val('')

                    }
                })

                $(document).on('click','.btn-delete-tools',function(){
                    if($(this).attr('id') == '-'){
                        let attr_data = $(this).attr('data');

                        Swal.fire({
                            title: 'Delete Tools?',
                            text: "Deleted Tools will be permanently lost!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#7F16A7',
                            confirmButtonText: 'Yes, Delete',
                        }).then(function (result) {
                            if (result.value) {
                                const index = arr_tools.indexOf(attr_data);
                                if (index > -1) {
                                    arr_tools.splice(index, 1);
                                    initTools(arr_tools, global_id);
                                }
                            }
                        })
                        $('.swal2-title').addClass('justify-content-center')
                    }else{
                        let attr_id = $(this).attr('id');
                        let attr_data = $(this).attr('data');

                        Swal.fire({
                            title: 'Delete Tools?',
                            text: "Deleted Tools will be permanently lost!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#7F16A7',
                            confirmButtonText: 'Yes, Delete',
                        }).then(function (result) {
                            if (result.value) {
                                $.ajax({
                                    url: '{{url('admin/master/courses/classroom/tools')}}/'+attr_id,
                                    type: 'DELETE',
                                    dataType: 'json',
                                })
                                .done(function(res, xhr, meta) {
                                    initDataTools(attr_data)
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
            },
            initTools = (collections, id) => {
                if(id){
                    initDataTools(id)
                }else{
                    let element = '';
                    $.each(collections, function(index, data){
                        element += `<tr>
                                <td class="text-center">
                                    <i id="-" data="${data}" class="far fa-trash-alt icn text-danger btn-delete-tools"></i>
                                </td>
                                <td>${data}</td>
                            </tr>`;
                    })

                    $('#tools-tbody').html(element);
                }
            },
            initDataTools = (id) => {
                $.ajax({
                    url: '{{url('admin/master/courses/classroom/tools')}}/'+id,
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = '';
                    $.each(res.data, function(index, data){
                        element += `<tr>
                                <td class="text-center">
                                    <i id="${data.id}" data="${data.classroom_id}" class="far fa-trash-alt icn text-danger btn-delete-tools"></i>
                                </td>
                                <td>${data.text}</td>
                            </tr>`;
                    })

                    $.each(arr_tools, function(index, data){
                        element += `<tr>
                                <td class="text-center">
                                    <i id="-" data="${data}" class="far fa-trash-alt icn text-danger btn-delete-tools"></i>
                                </td>
                                <td>${data}</td>
                            </tr>`;
                    })

                    $('#tools-tbody').html(element);
                })
            },
            formSubmit = () => {
                $('#form-classroom').submit(function(event){
                    event.preventDefault();

                    let validate = ss_validate([
                        'classroom-category',
                    ]);

                    if(!validate){
                        return false;
                    }
                    let form_data = new FormData(this)

                    for (var i = 0; i < arr_tools.length; i++) {
                        form_data.append('tools[]', arr_tools[i]);
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
                        toastr.success(res.message, 'Success');
                        init_table.draw(false);
                        hideModal('modal-classroom');
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
                        $('#sub-classroom-category').val('');
                        $('.select-sub-category').hide();
                    }
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
