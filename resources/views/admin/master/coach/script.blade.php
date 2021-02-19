<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            let init_table, element_sosmed, option = new Array, element_package, selectdisplay1, selectdisplay2, init_number_input = 1;
            let data1 = new Array;
            let data2 = new Array;
            let init_select_expertise;
            $(document).ready(function() {
                initTable();
                formSubmit();
                initAction();
                getSosmed();
                getExpertise();
                initTreeTable();
                selectdisplay1 = new SlimSelect({
                    select: '#selectdisplay1',
                    closeOnSelect: false
                });
                selectdisplay2 = new SlimSelect({
                    select: '#selectdisplay2',
                    closeOnSelect: false
                });
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
                        { data: 'expertise_name' },
                        { data: 'suspend' },
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
                            data: "suspend",
                            render : function(data, type, full, meta) {
                                if(data){
                                    return `
                                    <div class="d-flex justify-content-center">
                                        <span class="switch switch-sm switch-outline switch-icon switch-success">
                                            <label>
                                            <input type="checkbox" class="btn-switch" checked="checked" name="suspend" id="btn-switch-${full.id}"/>
                                            <span></span>
                                            </label>
                                        </span>
                                    </div>
                                `;
                                }
                                else{
                                    return `
                                        <div class="d-flex justify-content-center">
                                            <span class="switch switch-sm switch-outline switch-icon switch-success">
                                                <label>
                                                <input type="checkbox" class="btn-switch" name="suspend" id="btn-switch-${full.id}"/>
                                                <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    `;
                                }
                            }
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
                                                <a href="{{url('admin/master/coach/view-calendar')}}/${full.id}">${data}</a>
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
                                    <a class="btn btn-sm btn-permission mr-1 btn-clean btn-icon" title="Permission" href="{{url('/admin/master/coach/permission')}}/${data}">
                                        <span class="svg-icon svg-icon-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <polygon fill="#000000" opacity="0.3" transform="translate(8.885842, 16.114158) rotate(-315.000000) translate(-8.885842, -16.114158) " points="6.89784488 10.6187476 6.76452164 19.4882481 8.88584198 21.6095684 11.0071623 19.4882481 9.59294876 18.0740345 10.9659914 16.7009919 9.55177787 15.2867783 11.0071623 13.8313939 10.8837471 10.6187476"/>
                                                <path d="M15.9852814,14.9852814 C12.6715729,14.9852814 9.98528137,12.2989899 9.98528137,8.98528137 C9.98528137,5.67157288 12.6715729,2.98528137 15.9852814,2.98528137 C19.2989899,2.98528137 21.9852814,5.67157288 21.9852814,8.98528137 C21.9852814,12.2989899 19.2989899,14.9852814 15.9852814,14.9852814 Z M16.1776695,9.07106781 C17.0060967,9.07106781 17.6776695,8.39949494 17.6776695,7.57106781 C17.6776695,6.74264069 17.0060967,6.07106781 16.1776695,6.07106781 C15.3492424,6.07106781 14.6776695,6.74264069 14.6776695,7.57106781 C14.6776695,8.39949494 15.3492424,9.07106781 16.1776695,9.07106781 Z" fill="#000000" transform="translate(15.985281, 8.985281) rotate(-315.000000) translate(-15.985281, -8.985281) "/>
                                                <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </a>
                                    <a href="{{url('/admin/master/coach/update')}}/${data}" title="Edit" class="btn btn-edit btn-sm btn-clean btn-icon mr-1">
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
                                    <a href="{{url('/admin/master/coach/config')}}/${data}" title="Configuration" class="btn btn-config btn-sm btn-clean btn-icon mr-1">
                                        <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Settings-1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z" fill="#000000"/>
                                                <path d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z" fill="#000000" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
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
                    $('#title').html('Tambah');
                    $('#form-coach').trigger("reset");
                    $('#form-coach').attr('action','{{url('admin/master/coach')}}');
                    $('#form-coach').attr('method','POST');
                    $('#form-coach').attr('data-form','insert');
                    $('.img-profile-edit').attr('src',`{{asset('assets/images/profile.png')}}`);
                    init_select_expertise.set([]);
                    $('#medsos').empty();

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

                    $('#title').html('Edit');
                    $('#form-coach').trigger("reset");
                    $('#form-coach').attr('action', $(this).attr('href'));
                    $('#form-coach').attr('method','POST');
                    $('#form-coach').attr('data-form','update');

                    $('#form-coach').find('input[name="name"]').val(data.name);
                    $('#form-coach').find('input[name="username"]').val(data.username);
                    $('#form-coach').find('input[name="email"]').val(data.email);
                    $('#form-coach').find('textarea[name="profil_description"]').val(data.description);
                    $('#form-coach').find('input[name="phone"]').val(data.phone);
                    $('#form-coach').find('input[name="profile_avatar"]').attr('required',false);
                    init_select_expertise.set(data.expertise_id);
                    $('.img-profile-edit').attr('src',`${data.image_url}`);

                    getCoachSosmed(data.id);
                    $('.password-setting').removeClass('col-6');
                    $('.password-setting').empty();

                    showModal('modal-coach');
                });

                $(document).on('click', '.btn-delete', function(event){
                    event.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Delete Coach?',
                        text: "Deleted Coach will be permanently lost!",
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
                });

                $(document).on('click','.btn-switch',function(event){
                    event.preventDefault();
                    let check = $(this).is(":checked");
                    let element = $(this);
                    var data = init_table.row($(this).parents('tr')).data();
                    let url = '';
                    let status = '';
                    if(check){
                        status = "Activate";
                        url = `{{ url('admin/master/coach/activate-suspend') }}/${data.id}`;
                    }else{
                        status = "Suspend";
                        url = `{{ url('admin/master/coach/suspend') }}/${data.id}`;
                    }
                    Swal.fire({
                        title: `${status} Coach?`,
                        text: "Coach will be active!.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7F16A7',
                        confirmButtonText: `Yes, ${status}`,
                    }).then(function (result) {
                        if (result.value) {
                            $.ajax({
                                url: url,
                                type: 'post',
                            })
                            .done(function(res, xhr, meta) {
                                if (res.status == 200) {
                                    toastr.success(res.message, 'Success');
                                    if($(`#btn-switch-${data.id}`).is(":checked")){
                                        $(`#btn-switch-${data.id}`).prop('checked', false);
                                    }else{
                                        $(`#btn-switch-${data.id}`).prop('checked', true);
                                    }
                                }
                            })
                            .fail(function(res) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() { });
                        }
                    })
                    $('.swal2-title').addClass('justify-content-center')
                });

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

                $(document).on('click','.btn-config',function(event){
                    event.preventDefault();
                    init_number_input = 1;

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-config').trigger("reset");
                    $('#form-config').attr('action', $(this).attr('href'));
                    $('#form-config').attr('method','POST');

                    $('.add-package').show();

                    selectdisplay1.set([]);
                    selectdisplay2.set([]);
                    let init_package_type = 0;
                    let package_type_1,package_type_2;
                    let increment = 0;
                    $.ajax({
                        url: `{{ url('admin/master/coach/class') }}/${data.id}`,
                        type: `GET`,
                    })
                    .done(function(res, xhr, meta) {

                        $.each(res.data, function(index, data) {
                            if(init_package_type == 0){
                                init_package_type = data.package_type
                                data1[index] = data.classroom_id;
                                package_type_1 = data.package_type;
                            }
                            else if(init_package_type == data.package_type){
                                data1[index] = data.classroom_id;
                            }
                            else{
                                if(increment==0){
                                    $('.other-package').show();
                                    package_type_2 = data.package_type;

                                }
                                data2[increment] = data.classroom_id;
                                increment++;
                            }
                        });
                        if(package_type_1){
                            $('#package1').val(package_type_1).change();
                        }
                        if(package_type_2){
                            $('#package2').val(package_type_2).change();
                        }
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

                    });

                    showModal('modal-config');
                });

                $(document).on('change','.package',function(event){
                    event.preventDefault();
                    if($(this).val()){
                        if($(this).data('number') == 1){
                            selectdisplay1.setData([]);
                            $.ajax({
                                url: `{{ url('public/get-class') }}/${$(this).val()}`,
                                type: `GET`,
                            })
                            .done(function(res, xhr, meta) {
                                let element = ``;
                                $.each(res.data, function(index, data) {
                                    element += `<option value="${data.id}">${data.name}</option>`
                                });
                                $('#selectdisplay1').html(element);

                                if(selectdisplay1){
                                    selectdisplay1.destroy();
                                }

                                selectdisplay1 = new SlimSelect({
                                    select: '#selectdisplay1',
                                    closeOnSelect: false
                                });
                                selectdisplay1.set(data1);
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

                            });
                        }
                        else{
                            selectdisplay2.setData([]);
                            $.ajax({
                                url: `{{ url('public/get-class') }}/${$(this).val()}`,
                                type: `GET`,
                            })
                            .done(function(res, xhr, meta) {
                                let element = ``;
                                $.each(res.data, function(index, data) {
                                    element += `<option value="${data.id}">${data.name}</option>`
                                });
                                $('#selectdisplay2').html(element);

                                if(selectdisplay2){
                                    selectdisplay2.destroy();
                                }

                                selectdisplay2 = new SlimSelect({
                                    select: '#selectdisplay2',
                                    closeOnSelect: false
                                });

                                selectdisplay2.set(data2);
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

                            });
                        }
                    }
                });

                $(document).on('change','#package1',function(event){
                    event.preventDefault();
                    let package1 = $(this).val();
                    if(package1 == 1){
                        $('#package2').html(`
                            <option value="">Pilih Package</option>
                            <option value="2">Reguler</option>
                        `);
                    }
                    else{
                        $('#package2').html(`
                            <option value="">Pilih Package</option>
                            <option value="1">Spesial</option>
                        `);
                    }

                    if(selectdisplay2){
                        selectdisplay2.destroy();
                    }

                    selectdisplay2 = new SlimSelect({
                        select: '#selectdisplay2',
                        closeOnSelect: false
                    });
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
                                        <input type="text" name="url_sosmed[]" class="form-control" required value="${data.url}" placeholder="Alamat URL">
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
                        element += `</select>
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
                    let expertise = $('#expertise').val();
                    if(form == 'insert'){
                        if(!avatar){
                            return toastr.error('Avatar harus diisi', 'Failed')
                        }

                        if(!expertise){
                            return toastr.error('Expertise harus diisi', 'Failed')
                        }
                    }else{
                        if(!expertise){
                            return toastr.error('Expertise harus diisi', 'Failed')
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

                $('#form-config').submit(function(event){
                    event.preventDefault();
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
                        toastr.success(res.message, 'Success');
                        init_table.draw(false);
                        hideModal('modal-config');
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
                                <input type="hidden" name="init_sosmed_coach[]" class="init_sosmed_coach">
                                <div class="input-group">
                                    <input type="text" name="url_sosmed[]" class="form-control" required placeholder="Alamat URL">
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

                    element_sosmed += `</select>
                                </div>
                            </div>
                            <div class="col-1">
                                <a href="javascript:void(0)" class="delete-medsos" data-url="">
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
                    </div>`;
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            getExpertise = () =>{
                $.ajax({
                    url: `{{ url('public/get-expertise') }}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Pilih Expertise</option>`;
                    $.each(res.data, function(index, data) {
                        element += `<option value="${data.id}">${data.name}</option>`;
                    });
                    $('#expertise').html(element);
                    init_select_expertise = new SlimSelect({
                        select: '#expertise',
                        searchPlaceholder:'Search Expertise',
                    });
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

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
                    url: '{{ asset('data/coach.json') }}'
                },
                lazyLoad: function(event, data) {
                    data.result = {url: '{{ asset('data/coach.json') }}'}
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
