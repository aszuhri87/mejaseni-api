<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            let check_init_filter = false;
            let package_type = null;
            let init_class_sub_category = null;
            let init_class_category = null;
            let month = new Array();
                month['01'] = 'Januari';
                month['02'] = 'Februari';
                month['03'] = 'Maret';
                month['04'] = 'April';
                month['05'] = 'Mei';
                month['06'] = 'Juni';
                month['07'] = 'Juli';
                month['08'] = 'agustus';
                month['09'] = 'September';
                month['10'] = 'Oktober';
                month['11'] = 'November';
                month['12'] = 'Desember';

            $(document).ready(function() {
                getClassroomCategory();
                formSubmit();
                initAction();
                // getSubCategory();
                // getSessionVideo();
                // initPackage();
                // initSpecialOffer();


            });

            const initSplide = () => {
                new Splide('#category-splide', {
                    type: 'loop',
                    arrow: false,
                    perPage: 5,
                    perMove: 2,
                    pagination: false,
                    padding: {
                        right: '0rem',
                        left: '0rem',
                    },
                    breakpoints: {
                        1025: {
                            perPage: 3,
                            padding: {
                                right: '0rem',
                                left: '0rem',
                            },
                        },
                        991: {
                            perPage: 2,
                            padding: {
                                right: '0rem',
                                left: '0rem',
                            },
                        },
                        640: {
                            perPage: 1,
                            padding: {
                                right: '3rem',
                                left: '3rem',
                            },
                        },
                    }
                }).mount();
            },
            initAction = () => {
                $(document).on('click', '#add-btn', function(event){
                    event.preventDefault();

                    $('#form-new-package').trigger("reset");
                    $('#form-new-package').attr('action','{{url('admin/master/courses/classroom-category')}}');
                    $('#form-new-package').attr('method','POST');

                    showModal('modal-invoice');
                });

                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-new-package').trigger("reset");
                    $('#form-new-package').attr('action', $(this).attr('href'));
                    $('#form-new-package').attr('method','PUT');

                    $('#form-new-package').find('input[name="name"]').val(data.name);

                    showModal('modal-invoice');
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

                $(document).on('click','.master-lesson',function(event){
                    event.preventDefault();
                    $('#init-class').html($(this).text()+' Class');
                    $('.btn-filter').addClass('btn-outline-primary').removeClass('btn-primary');
                    $(this).addClass('btn-primary').removeClass('btn-outline-primary');
                    getMasterLesson();
                })

                $(document).on('click','.filter',function(event){
                    event.preventDefault();
                    $('#init-class').html($(this).text()+' Class');

                    $('.btn-filter').addClass('btn-outline-primary').removeClass('btn-primary');
                    $(this).addClass('btn-primary').removeClass('btn-outline-primary');

                    package_type = $(this).data('package_type');
                    init_class_sub_category = $('#init_class_sub_category').val();
                    init_class_category = $('#init_class_category').val();

                    $.ajax({
                        url: `{{ url('student/new-package/get-package') }}`,
                        type: `GET`,
                        data:{
                            package_type : package_type,
                            init_class_category : init_class_category,
                            init_class_sub_category : init_class_sub_category,
                        }
                    })
                    .done(function(res, xhr, meta) {
                        let element = ``;
                        if(res.data.data.length > 0){
                            $.each(res.data.data, function(index, data) {
                                element += `
                                <div class="col-lg-6 mb-5">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card" style="border: none !important">
                                                <img class="card-img-top rounded" src="${data.image_url}" alt="Card image cap" style="height: 300px !important">
                                                <h5 class="card-title mt-5" id="title-package">${data.classroom_name}</h5>
                                                <div class="card-toolbar">
                                                    <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                                        {{-- description --}}
                                                        <li class="nav-item mr-3">
                                                            <a class="nav-link active" data-toggle="tab" href="#tab-description-${data.classroom_id}">
                                                                <span class="nav-icon">
                                                                    <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Text/Article.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <rect x="0" y="0" width="24" height="24"/>
                                                                            <rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5"/>
                                                                            <path d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L12.5,10 C13.3284271,10 14,10.6715729 14,11.5 C14,12.3284271 13.3284271,13 12.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z" fill="#000000" opacity="0.3"/>
                                                                        </g>
                                                                    </svg><!--end::Svg Icon--></span>
                                                                </span>

                                                                <span class="nav-text font-size-lg">Deskripsi</span>
                                                            </a>
                                                        </li>
                                                        {{-- end description --}}

                                                        {{-- Coach --}}
                                                        <li class="nav-item mr-3">
                                                            <a class="nav-link" data-toggle="tab" href="#tab-coach-${data.classroom_id}">
                                                                <span class="nav-icon">
                                                                    <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/User.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                                            <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                            <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                        </g>
                                                                    </svg><!--end::Svg Icon--></span>
                                                                </span>
                                                                <span class="nav-text font-size-lg">Coach</span>
                                                            </a>
                                                        </li>
                                                        {{-- end Coach --}}

                                                        {{-- tools --}}
                                                        <li class="nav-item mr-3">
                                                            <a class="nav-link" data-toggle="tab" href="#tab-tools-${data.classroom_id}">
                                                                <span class="nav-icon">
                                                                    <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <rect x="0" y="0" width="24" height="24"/>
                                                                            <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                                                            <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                                                        </g>
                                                                    </svg><!--end::Svg Icon--></span>
                                                                </span>

                                                                <span class="nav-text font-size-lg">Tools</span>
                                                            </a>
                                                        </li>
                                                        {{-- end tools --}}
                                                    </ul>
                                                </div>
                                                <div class="tab-content" style="height:200px !important">
                                                    {{-- description --}}
                                                    <div class="tab-pane show active" id="tab-description-${data.classroom_id}" role="tabpanel">
                                                        <div class="form-group mt-5 overflow-auto" style="height:130px !important">
                                                            ${data.description}
                                                        </div>
                                                        <div class="footer d-flex justify-content-between">
                                                            <div class="pt-3">
                                                                <span> <h4>Rp. ${numeral(data.price).format('0,0')},-</h4> </span>
                                                            </div>
                                                            <div class="pt-3">
                                                                <span>${data.session_total} Sesi </span><span>@${data.session_duration}Menit</span>
                                                            </div>
                                                            `;
                                                            // if(!data.is_buy){
                                                                element +=`
                                                                <div id="${data.classroom_id}">
                                                                    <button type="button" class="btn btn-primary btn-registration" data-type="1" data-id="${data.classroom_id}" data-classroom_name="${data.classroom_name}" data-price="${data.price}">
                                                                        <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                                                <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                                <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                            </g>
                                                                        </svg><!--end::Svg Icon--></span>
                                                                        Daftar Kelas
                                                                    </button>
                                                                </div>`;
                                                            // }
                                                    element+=`
                                                        </div>
                                                    </div>
                                                    {{-- end description --}}

                                                    {{-- coach --}}
                                                    <div class="tab-pane show" id="tab-coach-${data.classroom_id}" role="tabpanel">
                                                        <div class="form-group mt-5">
                                                            <div style="height:180px !important" class="overflow-auto">
                                                            <div class="row">`;
                                if(data.coach.length > 0){
                                    $.each(data.coach, function(index, item) {
                                        element += `
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-5 mb-3">
                                                        <img src="${item.coach_image_url}" class="rounded" width="50" height="50"/>
                                                    </div>
                                                    <div class="d-flex flex-column font-weight-bold">
                                                        <p class="mb-1 font-size-lg">${item.coach_name}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                                    });
                                }
                                else{
                                    element += `
                                            <div class="col-12">
                                                <div class="mb-3 d-flex align-items-center">
                                                    <p class="mb-1 font-size-lg text-muted">Coach Not Available</p>
                                                </div>
                                            </div>
                                        `;
                                }
                                element += `
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- end coach --}}

                                                    {{-- tools --}}
                                                    <div class="tab-pane show" id="tab-tools-${data.classroom_id}" role="tabpanel">
                                                        <div class="form-group mt-5">

                                                                `;
                                if(data.tools.length >0){
                                    element += `<ul>`;
                                    $.each(data.tools, function(index, value) {
                                        element += `<li>${value.tool_name}</li>`;
                                    });
                                    element += `</ul>`;
                                }
                                else{
                                    element += `<p class="mb-1 font-size-lg text-muted">Tools Not Available</p>`;
                                }
                                element+=`

                                                        </div>
                                                    </div>
                                                    {{-- end tools --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                            });

                            let element_paginate = `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 previous-package"><i class="ki ki-bold-arrow-back icon-xs"></i></a>`;
                            let last_page = res.data.last_page;

                            $.each(res.data.links,function(index,data){
                                if(index <= last_page){
                                    if(index > 0){
                                        if(index == 1){
                                            element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 active paginate-package">${index}</a>`;
                                        }else{
                                            element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 paginate-package">${index}</a>`;
                                        }
                                    }
                                }
                            });
                            element_paginate += `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 next-package"><i class="ki ki-bold-arrow-next icon-xs"></i></a>`;

                            $('#package-paginate').html(element_paginate);
                            $('#conference-package').html(element);
                        }else{
                            element+=`
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body text-muted text-center">
                                            <h4>Class Not Available</h4>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('#package-paginate').empty();
                            $('#conference-package').html(element);
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {

                    });
                })

                $(document).on('click','.special-offer-detail',function(event){
                    event.preventDefault();
                    KTApp.blockPage({
                        overlayColor: '#000000',
                        state: 'primary',
                        opacity: 0.5,
                        message: 'Processing...'
                    });
                    let id = $(this).data('id');
                    $.ajax({
                        url: `{{url('student/new-package/get-special-offer-detail')}}/${id}`,
                        type: 'GET',
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        if (res.status == 200) {
                            let element = '';
                            let data = res.data;
                            element += `

                                <div class="card">
                                    <div class="card-body">
                                        <div class="card" style="border: none !important">
                                            <img class="card-img-top rounded" src="${data.image_url}" alt="Card image cap" style="height: 300px !important">
                                            <h5 class="card-title mt-5" id="title-package">${data.classroom_name}</h5>
                                            <div class="card-toolbar">
                                                <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                                    {{-- description --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link active" data-toggle="tab" href="#tab-description-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Text/Article.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5"/>
                                                                        <path d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L12.5,10 C13.3284271,10 14,10.6715729 14,11.5 C14,12.3284271 13.3284271,13 12.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z" fill="#000000" opacity="0.3"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Deskripsi</span>
                                                        </a>
                                                    </li>
                                                    {{-- end description --}}

                                                    {{-- Coach --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-coach-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/User.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>
                                                            <span class="nav-text font-size-lg">Coach</span>
                                                        </a>
                                                    </li>
                                                    {{-- end Coach --}}

                                                    {{-- tools --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-tools-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                                                        <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Tools</span>
                                                        </a>
                                                    </li>
                                                    {{-- end tools --}}
                                                </ul>
                                            </div>
                                            <div class="tab-content" style="height:200px !important">
                                                {{-- description --}}
                                                <div class="tab-pane show active" id="tab-description-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5 overflow-auto" style="height:130px !important">
                                                        ${data.description}
                                                    </div>
                                                    <div class="footer d-flex justify-content-between">
                                                        <div class="pt-3">
                                                            <span>
                                                                <s> <span class="text-muted"> Rp. ${numeral(data.price).format('0,0')},- </span> </s>
                                                                <h4>Rp. ${numeral(data.price_discount).format('0,0')},-</h4>
                                                            </span>
                                                        </div>
                                                        <div class="pt-3">
                                                            <span>${data.session_total} Sesi </span><span>@${data.session_duration}Menit</span>
                                                        </div>
                                                        `;
                                                        // if(!data.is_buy){
                                                            element +=`
                                                            <div id="${data.classroom_id}">
                                                                <button type="button" class="btn btn-primary btn-registration" data-type="1" data-id="${data.classroom_id}" data-classroom_name="${data.classroom_name}" data-price="${data.price}">
                                                                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                                            <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                            <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                        </g>
                                                                    </svg><!--end::Svg Icon--></span>
                                                                    Daftar Kelas
                                                                </button>
                                                            </div>`;
                                                        // }
                                                element+=`
                                                    </div>
                                                </div>
                                                {{-- end description --}}

                                                {{-- coach --}}
                                                <div class="tab-pane show" id="tab-coach-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5">
                                                        <div style="height:180px !important" class="overflow-auto">
                                                        <div class="row">`;
                            if(data.coach.length > 0){
                                $.each(data.coach, function(index, item) {
                                    element += `
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-5 mb-3">
                                                    <img src="${item.coach_image_url}" class="rounded" width="50" height="50"/>
                                                </div>
                                                <div class="d-flex flex-column font-weight-bold">
                                                    <p class="mb-1 font-size-lg">${item.coach_name}</p>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                            else{
                                element += `
                                        <div class="col-12">
                                            <div class="mb-3 d-flex align-items-center">
                                                <p class="mb-1 font-size-lg text-muted">Coach Not Available</p>
                                            </div>
                                        </div>
                                    `;
                            }
                            element += `
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- end coach --}}

                                                {{-- tools --}}
                                                <div class="tab-pane show" id="tab-tools-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5">

                                                            `;
                            if(data.tools.length >0){
                                element += `<ul>`;
                                $.each(data.tools, function(index, value) {
                                    element += `<li>${value.tool_name}</li>`;
                                });
                                element += `</ul>`;
                            }
                            else{
                                element += `<p class="mb-1 font-size-lg text-muted">Tools Not Available</p>`;
                            }
                            element+=`

                                                    </div>
                                                </div>
                                                {{-- end tools --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            `;

                            $('#content-modal-special-offer').html(element);
                            KTApp.unblockPage();
                            showModal('modal-special-offer');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                })

                $(document).on('click','.btn-registration',function(event){
                    event.preventDefault();
                    let classroom_id = $(this).data('classroom_id');
                    let price = $(this).data('price');
                    let classroom_name = $(this).data('classroom_name');
                    let id = $(this).data('id');
                    let type = $(this).data('type');

                    $('#id').val(id);
                    $('#type').val(type);
                    $('#classroom-name').html(`Kelas "${classroom_name}"`);
                    $('#price').html(`Rp. ${numeral(price).format('0,0')}`);
                    $('#total-price').html(`Rp. ${numeral(price).format('0,0')}`);
                    $('#form-new-package').attr('action',`{{url('student/add-to-cart')}}`);
                    showModal('modal-new-package');
                });

                $(document).on('click','.btn-registration-master-lesson',function(event){
                    event.preventDefault();
                    let master_lesson_id = $(this).data('id');
                    let price = $(this).data('price');
                    let master_lesson_name = $(this).data('master_lesson_name');
                    let id = $(this).data('id');
                    let type = $(this).data('type');

                    $('#id').val(id);
                    $('#type').val(type);
                    $('#classroom-name').html(`Kelas "${master_lesson_name}"`);
                    $('#price').html(`Rp. ${numeral(price).format('0,0')}`);
                    $('#total-price').html(`Rp. ${numeral(price).format('0,0')}`);
                    $('#form-new-package').attr('action',`{{url('student/add-to-cart')}}`);
                    showModal('modal-new-package');
                });

                $(document).on('click','.btn-category',function(event){
                    event.preventDefault();
                    KTApp.blockPage({
                        overlayColor: '#000000',
                        state: 'primary',
                        opacity: 0.5,
                        message: 'Processing...'
                    });
                    let id = $(this).data('id');

                    $('.btn-category, btn-primary').addClass('btn-outline-primary').removeClass('btn-primary');
                    $('.btn-filter').addClass('btn-outline-primary').removeClass('btn-primary');
                    $(this).addClass('btn-primary').removeClass('btn-outline-primary');

                    $('#init_class_sub_category').val($(this).data('id'));
                    $('#init-class').html('Regular Class');
                    $('#filter-2').addClass('btn-primary').removeClass('btn-outline-primary');
                    getPackageBySubCategory(id,2);
                    getSessionVideo(id);
                });

                $(document).on('click','.class-category-filter__wrapper',function(event){
                    $('#init_class_category').val( $(this).data('id'));
                    $('.class-category-filter__wrapper').removeClass('class-category-selected');
                    $(this).addClass('class-category-selected');

                    $('#init-class').html('Regular Class');
                    $('.btn-filter').addClass('btn-outline-primary').removeClass('btn-primary');
                    $('#filter-2').addClass('btn-primary').removeClass('btn-outline-primary');

                    let classroom_category_id = $(this).data('id');
                    getSubCategory(classroom_category_id);

                });

                // paginate package
                $(document).on('click','.paginate-package',function(event){
                    event.preventDefault();
                    $('.paginate-package').removeClass('active');
                    $(this).addClass('active');
                    $.ajax({
                        url: $(this).attr('href'),
                        type: `GET`,
                        data: {
                            package_type : package_type,
                            init_class_sub_category : init_class_sub_category,
                            init_class_category : init_class_category,
                        }
                    })
                    .done(function(res, xhr, meta) {
                        let element = ``;
                        $.each(res.data.data, function(index, data) {
                            element += `
                            <div class="col-lg-6 mb-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card" style="border: none !important">
                                            <img class="card-img-top rounded" src="${data.image_url}" alt="Card image cap" style="height: 300px !important">
                                            <h5 class="card-title mt-5" id="title-package">${data.classroom_name}</h5>
                                            <div class="card-toolbar">
                                                <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                                    {{-- description --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link active" data-toggle="tab" href="#tab-description-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Text/Article.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5"/>
                                                                        <path d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L12.5,10 C13.3284271,10 14,10.6715729 14,11.5 C14,12.3284271 13.3284271,13 12.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z" fill="#000000" opacity="0.3"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Deskripsi</span>
                                                        </a>
                                                    </li>
                                                    {{-- end description --}}

                                                    {{-- Coach --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-coach-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/User.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>
                                                            <span class="nav-text font-size-lg">Coach</span>
                                                        </a>
                                                    </li>
                                                    {{-- end Coach --}}

                                                    {{-- tools --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-tools-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                                                        <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Tools</span>
                                                        </a>
                                                    </li>
                                                    {{-- end tools --}}
                                                </ul>
                                            </div>
                                            <div class="tab-content" style="height:200px !important">
                                                {{-- description --}}
                                                <div class="tab-pane show active" id="tab-description-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5 overflow-auto" style="height:130px !important">
                                                        ${data.description}
                                                    </div>
                                                    <div class="footer d-flex justify-content-between">
                                                        <div class="pt-3">
                                                            <span> <h4>Rp. ${numeral(data.price).format('0,0')},-</h4> </span>
                                                        </div>
                                                        <div class="pt-3">
                                                            <span>${data.session_total} Sesi </span><span>@${data.session_duration}Menit</span>
                                                        </div>
                                                        `;
                                                        // if(!data.is_buy){
                                                            element +=`
                                                            <div id="${data.classroom_id}">
                                                                <button type="button" class="btn btn-primary btn-registration" data-type="1" data-id="${data.classroom_id}" data-classroom_name="${data.classroom_name}" data-price="${data.price}">
                                                                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                                            <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                            <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                        </g>
                                                                    </svg><!--end::Svg Icon--></span>
                                                                    Daftar Kelas
                                                                </button>
                                                            </div>`;
                                                        // }
                                                element+=`
                                                    </div>
                                                </div>
                                                {{-- end description --}}

                                                {{-- coach --}}
                                                <div class="tab-pane show" id="tab-coach-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5">
                                                        <div style="height:180px !important" class="overflow-auto">
                                                        <div class="row">`;
                            if(data.coach.length > 0){
                                $.each(data.coach, function(index, item) {
                                    element += `
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-5 mb-3">
                                                    <img src="${item.coach_image_url}" class="rounded" width="50" height="50"/>
                                                </div>
                                                <div class="d-flex flex-column font-weight-bold">
                                                    <p class="mb-1 font-size-lg">${item.coach_name}</p>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                            else{
                                element += `
                                        <div class="col-12">
                                            <div class="mb-3 d-flex align-items-center">
                                                <p class="mb-1 font-size-lg text-muted">Coach Not Available</p>
                                            </div>
                                        </div>
                                    `;
                            }
                            element += `
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- end coach --}}

                                                {{-- tools --}}
                                                <div class="tab-pane show" id="tab-tools-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5">

                                                            `;
                            if(data.tools.length >0){
                                element += `<ul>`;
                                $.each(data.tools, function(index, value) {
                                    element += `<li>${value.tool_name}</li>`;
                                });
                                element += `</ul>`;
                            }
                            else{
                                element += `<p class="mb-1 font-size-lg text-muted">Tools Not Available</p>`;
                            }
                            element+=`

                                                    </div>
                                                </div>
                                                {{-- end tools --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                        });
                        $('#conference-package').html(element);
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {

                    });
                });

                $(document).on('click','.next-package',function(event){
                    event.preventDefault();
                    let number = $('.paginate-package.active').html();
                    let page = parseInt(number)+1;

                    $('.paginate-package').each(function(index, obj){
                        if($(this).html() == page){
                            $('.paginate-package').removeClass('active');
                            $(this).addClass('active');

                            $.ajax({
                                url: `{{url('student/new-package/get-package')}}?page=${page}`,
                                type: `GET`,
                            })
                            .done(function(res, xhr, meta) {
                                let element = ``;
                                $.each(res.data.data, function(index, data) {
                                    element += `
                                    <div class="col-lg-6 mb-5">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card" style="border: none !important">
                                                    <img class="card-img-top rounded" src="${data.image_url}" alt="Card image cap" style="height: 300px !important">
                                                    <h5 class="card-title mt-5" id="title-package">${data.classroom_name}</h5>
                                                    <div class="card-toolbar">
                                                        <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                                            {{-- description --}}
                                                            <li class="nav-item mr-3">
                                                                <a class="nav-link active" data-toggle="tab" href="#tab-description-${data.classroom_id}">
                                                                    <span class="nav-icon">
                                                                        <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Text/Article.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                                <rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5"/>
                                                                                <path d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L12.5,10 C13.3284271,10 14,10.6715729 14,11.5 C14,12.3284271 13.3284271,13 12.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z" fill="#000000" opacity="0.3"/>
                                                                            </g>
                                                                        </svg><!--end::Svg Icon--></span>
                                                                    </span>

                                                                    <span class="nav-text font-size-lg">Deskripsi</span>
                                                                </a>
                                                            </li>
                                                            {{-- end description --}}

                                                            {{-- Coach --}}
                                                            <li class="nav-item mr-3">
                                                                <a class="nav-link" data-toggle="tab" href="#tab-coach-${data.classroom_id}">
                                                                    <span class="nav-icon">
                                                                        <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/User.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                            </g>
                                                                        </svg><!--end::Svg Icon--></span>
                                                                    </span>
                                                                    <span class="nav-text font-size-lg">Coach</span>
                                                                </a>
                                                            </li>
                                                            {{-- end Coach --}}

                                                            {{-- tools --}}
                                                            <li class="nav-item mr-3">
                                                                <a class="nav-link" data-toggle="tab" href="#tab-tools-${data.classroom_id}">
                                                                    <span class="nav-icon">
                                                                        <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                                <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                                                                <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                                                            </g>
                                                                        </svg><!--end::Svg Icon--></span>
                                                                    </span>

                                                                    <span class="nav-text font-size-lg">Tools</span>
                                                                </a>
                                                            </li>
                                                            {{-- end tools --}}
                                                        </ul>
                                                    </div>
                                                    <div class="tab-content" style="height:200px !important">
                                                        {{-- description --}}
                                                        <div class="tab-pane show active" id="tab-description-${data.classroom_id}" role="tabpanel">
                                                            <div class="form-group mt-5 overflow-auto" style="height:130px !important">
                                                                ${data.description}
                                                            </div>
                                                            <div class="footer d-flex justify-content-between">
                                                                <div class="pt-3">
                                                                    <span> <h4>Rp. ${numeral(data.price).format('0,0')},-</h4> </span>
                                                                </div>
                                                                <div class="pt-3">
                                                                    <span>${data.session_total} Sesi </span><span>@${data.session_duration}Menit</span>
                                                                </div>
                                                                `;
                                                                // if(!data.is_buy){
                                                                    element +=`
                                                                    <div id="${data.classroom_id}">
                                                                        <button type="button" class="btn btn-primary btn-registration" data-type="1" data-id="${data.classroom_id}" data-classroom_name="${data.classroom_name}" data-price="${data.price}">
                                                                            <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                                                    <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                                    <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                                </g>
                                                                            </svg><!--end::Svg Icon--></span>
                                                                            Daftar Kelas
                                                                        </button>
                                                                    </div>`;
                                                                // }
                                                        element+=`
                                                            </div>
                                                        </div>
                                                        {{-- end description --}}

                                                        {{-- coach --}}
                                                        <div class="tab-pane show" id="tab-coach-${data.classroom_id}" role="tabpanel">
                                                            <div class="form-group mt-5">
                                                                <div style="height:180px !important" class="overflow-auto">
                                                                <div class="row">`;
                                    if(data.coach.length > 0){
                                        $.each(data.coach, function(index, item) {
                                            element += `
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mr-5 mb-3">
                                                            <img src="${item.coach_image_url}" class="rounded" width="50" height="50"/>
                                                        </div>
                                                        <div class="d-flex flex-column font-weight-bold">
                                                            <p class="mb-1 font-size-lg">${item.coach_name}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
                                        });
                                    }
                                    else{
                                        element += `
                                                <div class="col-12">
                                                    <div class="mb-3 d-flex align-items-center">
                                                        <p class="mb-1 font-size-lg text-muted">Coach Not Available</p>
                                                    </div>
                                                </div>
                                            `;
                                    }
                                    element += `
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- end coach --}}

                                                        {{-- tools --}}
                                                        <div class="tab-pane show" id="tab-tools-${data.classroom_id}" role="tabpanel">
                                                            <div class="form-group mt-5">

                                                                    `;
                                    if(data.tools.length >0){
                                        element += `<ul>`;
                                        $.each(data.tools, function(index, value) {
                                            element += `<li>${value.tool_name}</li>`;
                                        });
                                        element += `</ul>`;
                                    }
                                    else{
                                        element += `<p class="mb-1 font-size-lg text-muted">Tools Not Available</p>`;
                                    }
                                    element+=`

                                                            </div>
                                                        </div>
                                                        {{-- end tools --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    `;
                                });
                                $('#conference-package').html(element);
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() {

                            });

                            return false;
                        }
                    });

                });

                $(document).on('click','.previous-package',function(event){
                    event.preventDefault();
                    let number = $('.paginate-package.active').html();
                    let page = parseInt(number)-1;

                    $('.paginate-package').each(function(index, obj){
                        if($(this).html() == page){
                            $('.paginate-package').removeClass('active');
                            $(this).addClass('active');

                            $.ajax({
                                url: `{{url('student/new-package/get-package')}}?page=${page}`,
                                type: `GET`,
                            })
                            .done(function(res, xhr, meta) {
                                let element = ``;
                                $.each(res.data.data, function(index, data) {
                                    element += `
                                    <div class="col-lg-6 mb-5">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card" style="border: none !important">
                                                    <img class="card-img-top rounded" src="${data.image_url}" alt="Card image cap" style="height: 300px !important">
                                                    <h5 class="card-title mt-5" id="title-package">${data.classroom_name}</h5>
                                                    <div class="card-toolbar">
                                                        <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                                            {{-- description --}}
                                                            <li class="nav-item mr-3">
                                                                <a class="nav-link active" data-toggle="tab" href="#tab-description-${data.classroom_id}">
                                                                    <span class="nav-icon">
                                                                        <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Text/Article.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                                <rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5"/>
                                                                                <path d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L12.5,10 C13.3284271,10 14,10.6715729 14,11.5 C14,12.3284271 13.3284271,13 12.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z" fill="#000000" opacity="0.3"/>
                                                                            </g>
                                                                        </svg><!--end::Svg Icon--></span>
                                                                    </span>

                                                                    <span class="nav-text font-size-lg">Deskripsi</span>
                                                                </a>
                                                            </li>
                                                            {{-- end description --}}

                                                            {{-- Coach --}}
                                                            <li class="nav-item mr-3">
                                                                <a class="nav-link" data-toggle="tab" href="#tab-coach-${data.classroom_id}">
                                                                    <span class="nav-icon">
                                                                        <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/User.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                            </g>
                                                                        </svg><!--end::Svg Icon--></span>
                                                                    </span>
                                                                    <span class="nav-text font-size-lg">Coach</span>
                                                                </a>
                                                            </li>
                                                            {{-- end Coach --}}

                                                            {{-- tools --}}
                                                            <li class="nav-item mr-3">
                                                                <a class="nav-link" data-toggle="tab" href="#tab-tools-${data.classroom_id}">
                                                                    <span class="nav-icon">
                                                                        <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                                <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                                                                <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                                                            </g>
                                                                        </svg><!--end::Svg Icon--></span>
                                                                    </span>

                                                                    <span class="nav-text font-size-lg">Tools</span>
                                                                </a>
                                                            </li>
                                                            {{-- end tools --}}
                                                        </ul>
                                                    </div>
                                                    <div class="tab-content" style="height:200px !important">
                                                        {{-- description --}}
                                                        <div class="tab-pane show active" id="tab-description-${data.classroom_id}" role="tabpanel">
                                                            <div class="form-group mt-5 overflow-auto" style="height:130px !important">
                                                                ${data.description}
                                                            </div>
                                                            <div class="footer d-flex justify-content-between">
                                                                <div class="pt-3">
                                                                    <span> <h4>Rp. ${numeral(data.price).format('0,0')},-</h4> </span>
                                                                </div>
                                                                <div class="pt-3">
                                                                    <span>${data.session_total} Sesi </span><span>@${data.session_duration}Menit</span>
                                                                </div>
                                                                `;
                                                                // if(!data.is_buy){
                                                                    element +=`
                                                                    <div id="${data.classroom_id}">
                                                                        <button type="button" class="btn btn-primary btn-registration" data-type="1" data-id="${data.classroom_id}" data-classroom_name="${data.classroom_name}" data-price="${data.price}">
                                                                            <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                                                    <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                                    <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                                </g>
                                                                            </svg><!--end::Svg Icon--></span>
                                                                            Daftar Kelas
                                                                        </button>
                                                                    </div>`;
                                                                // }
                                                        element+=`
                                                            </div>
                                                        </div>
                                                        {{-- end description --}}

                                                        {{-- coach --}}
                                                        <div class="tab-pane show" id="tab-coach-${data.classroom_id}" role="tabpanel">
                                                            <div class="form-group mt-5">
                                                                <div style="height:180px !important" class="overflow-auto">
                                                                <div class="row">`;
                                    if(data.coach.length > 0){
                                        $.each(data.coach, function(index, item) {
                                            element += `
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mr-5 mb-3">
                                                            <img src="${item.coach_image_url}" class="rounded" width="50" height="50"/>
                                                        </div>
                                                        <div class="d-flex flex-column font-weight-bold">
                                                            <p class="mb-1 font-size-lg">${item.coach_name}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
                                        });
                                    }
                                    else{
                                        element += `
                                                <div class="col-12">
                                                    <div class="mb-3 d-flex align-items-center">
                                                        <p class="mb-1 font-size-lg text-muted">Coach Not Available</p>
                                                    </div>
                                                </div>
                                            `;
                                    }
                                    element += `
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- end coach --}}

                                                        {{-- tools --}}
                                                        <div class="tab-pane show" id="tab-tools-${data.classroom_id}" role="tabpanel">
                                                            <div class="form-group mt-5">

                                                                    `;
                                    if(data.tools.length >0){
                                        element += `<ul>`;
                                        $.each(data.tools, function(index, value) {
                                            element += `<li>${value.tool_name}</li>`;
                                        });
                                        element += `</ul>`;
                                    }
                                    else{
                                        element += `<p class="mb-1 font-size-lg text-muted">Tools Not Available</p>`;
                                    }
                                    element+=`

                                                            </div>
                                                        </div>
                                                        {{-- end tools --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    `;
                                });
                                $('#conference-package').html(element);
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() {

                            });

                            return false;
                        }
                    });

                })
                //paginate video
                $(document).on('click','.paginate-video',function(event){
                    event.preventDefault();
                    $('.paginate-video').removeClass('active');
                    $(this).addClass('active');
                    let url = `{{url('student/new-package/get-session-video')}}?page=${$(this).html()}`;
                    paginateVideo(url);
                });

                $(document).on('click','.next-video',function(event){
                    event.preventDefault();
                    let number = $('.paginate-video.active').html();
                    let page = parseInt(number)+1;

                    $('.paginate-video').each(function(index, obj){
                        if($(this).html() == page){
                            $('.paginate-video').removeClass('active');
                            $(this).addClass('active');
                            let url = `{{url('student/new-package/get-session-video')}}?page=${page}`;
                            paginateVideo(url);
                        }
                    });
                });

                $(document).on('click','.previous-video',function(event){
                    event.preventDefault();
                    let number = $('.paginate-video.active').html();
                    let page = parseInt(number)-1;

                    $('.paginate-video').each(function(index, obj){
                        if($(this).html() == page){
                            $('.paginate-video').removeClass('active');
                            $(this).addClass('active');
                            let url = `{{url('student/new-package/get-session-video')}}?page=${page}`;
                            paginateVideo(url);
                        }
                    });
                });
            },
            initSpecialOffer = () => {
                $.ajax({
                    url: `{{url('student/new-package/get-special-offer')}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    if (res.status == 200) {
                        if(res.data.length > 0){
                            let element = `
                                <h5>Special Offer</h5>
                                <div class="row">
                            `;
                            $.each(res.data, function(index, data){
                                element += `
                                    <div class="col-2">
                                        <div class="card" >
                                            <div class="ribbon ribbon-clip ribbon-right">
                                                <div class="ribbon-target" style="top: 12px;">
                                                    <span class="ribbon-inner bg-warning"></span>- ${data.discount}%
                                                </div>
                                            </div>
                                            <img src="${data.image_url}" height="150px !important">
                                            <div class="card-body text-center" style="padding: 10px !important">
                                                <a href="javascript:void(0)" class="align-self-end btn btn-primary w-100 mt-5 special-offer-detail" data-id="${data.id}">Lihat</a>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });

                            element += `
                                </div>
                            `;

                            $('#special-offer').html(element);
                        }
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                    btn_loading('stop')
                });
            },
            formSubmit = () => {
                $('#form-new-package').submit(function(event){
                    event.preventDefault();
                    let id = $('#id').val();

                    btn_loading('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        if (res.status == 200) {
                            toastr.success(res.message, 'Success');
                            hideModal('modal-new-package');
                            $(`#${id}`).hide();
                            // getCart();
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
            },
            getMasterLesson = () =>{
                let init_sub_classroom_category = $('#init_class_sub_category').val();
                $.ajax({
                    url: `{{ url('student/new-package/get-master-lesson') }}`,
                    type: 'get',
                    data: {
                        sub_classroom_category : init_sub_classroom_category
                    }
                })
                .done(function(res, xhr, meta) {
                    let element = ``;
                    if(res.data.data.length > 0){
                        $.each(res.data.data, function(index, data) {
                            element += `
                            <div class="col-lg-6 mb-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card" style="border: none !important">
                                            <img class="card-img-top rounded" src="${data.poster}" alt="Card image cap" style="height: 300px !important">
                                            <h5 class="card-title mt-5" id="title-package">${data.name}</h5>
                                            <div class="card-toolbar">
                                                <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                                    {{-- description --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link active" data-toggle="tab" href="#tab-description-${data.id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Text/Article.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5"/>
                                                                        <path d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L12.5,10 C13.3284271,10 14,10.6715729 14,11.5 C14,12.3284271 13.3284271,13 12.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z" fill="#000000" opacity="0.3"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Deskripsi</span>
                                                        </a>
                                                    </li>
                                                    {{-- end description --}}

                                                    {{-- Event --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-event-${data.id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/User.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>
                                                            <span class="nav-text font-size-lg">Event</span>
                                                        </a>
                                                    </li>
                                                    {{-- end Event --}}

                                                    {{-- guest star --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-guest-star-${data.id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                                                        <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Guest Star</span>
                                                        </a>
                                                    </li>
                                                    {{-- end guest star --}}
                                                </ul>
                                            </div>
                                            <div class="tab-content" style="height:200px !important">
                                                {{-- description --}}
                                                <div class="tab-pane show active" id="tab-description-${data.id}" role="tabpanel">
                                                    <div class="form-group mt-5 overflow-auto" style="height:130px !important">
                                                        ${data.description}
                                                    </div>
                                                    <div class="footer d-flex justify-content-between">
                                                        <div class="pt-3">
                                                            <span> <h4>Rp. ${numeral(data.price).format('0,0')},-</h4> </span>
                                                        </div>
                                                        `;
                                                        if(!data.is_exist_cart && data.total_booking < data.slot){
                                                            element +=`
                                                            <div id="${data.id}">
                                                                <button type="button" class="btn btn-primary btn-registration-master-lesson" data-type="2" data-id="${data.id}" data-master_lesson_name="${data.name}" data-price="${data.price}">
                                                                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                                            <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                            <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                        </g>
                                                                    </svg><!--end::Svg Icon--></span>
                                                                    Daftar Kelas
                                                                </button>
                                                            </div>`;
                                                        }
                                                element+=`
                                                    </div>
                                                </div>
                                                {{-- end description --}}

                                                {{-- event --}}
                                                <div class="tab-pane show" id="tab-event-${data.id}" role="tabpanel">
                                                    <div class="form-group mt-5">
                                                        <div style="height:180px !important" class="overflow-auto">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <table class="table table-borderless" >
                                                                        <tr>
                                                                            <td width="40px">Date</td>
                                                                            <td width="5px">:</td>
                                                                            <td class="font-size-lg">${moment(data.datetime).format('DD')} ${month[moment(data.datetime).format('MM')]} ${moment(data.datetime).format('YYYY')}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="40px">Time</td>
                                                                            <td width="5px">:</td>
                                                                            <td class="font-size-lg">${moment(data.datetime).format('HH:mm')}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="40px">Slot</td>
                                                                            <td width="5px">:</td>
                                                                            <td class="font-size-lg">${data.total_booking}/${data.slot}</td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- end event --}}

                                                {{-- guest star --}}
                                                <div class="tab-pane show" id="tab-guest-star-${data.id}" role="tabpanel">
                                                    <div class="form-group mt-5">

                                                            `;
                                if(data.guest_star.length > 0){
                                $.each(data.guest_star, function(index, item) {
                                    element += `
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-5 mb-3">
                                                    <img src="${item.image}" class="rounded" width="50" height="50"/>
                                                </div>
                                                <div class="d-flex flex-column font-weight-bold">
                                                    <p class="mb-1 font-size-lg">${item.name}</p>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                            else{
                                element += `
                                        <div class="col-12">
                                            <div class="mb-3 d-flex align-items-center">
                                                <p class="mb-1 font-size-lg text-muted">Guest Star Not Found</p>
                                            </div>
                                        </div>
                                    `;
                            }
                            element+=`

                                                    </div>
                                                </div>
                                                {{-- end guest star --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                        });

                        let element_paginate = `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 previous-package"><i class="ki ki-bold-arrow-back icon-xs"></i></a>`;
                        let last_page = res.data.last_page;

                        $.each(res.data.links,function(index,data){
                            if(index <= last_page){
                                if(index > 0){
                                    if(index == 1){
                                        element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 active paginate-package">${index}</a>`;
                                    }else{
                                        element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 paginate-package">${index}</a>`;
                                    }
                                }
                            }
                        });
                        element_paginate += `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 next-package"><i class="ki ki-bold-arrow-next icon-xs"></i></a>`;

                        $('#package-paginate').html(element_paginate);
                        $('#conference-package').html(element);
                    }else{
                        element+=`
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-muted text-center">
                                        <h4>Master Lesson Not Available</h4>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#package-paginate').empty();
                        $('#conference-package').html(element);
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() { });
            },
            getSubCategory = (classroom_category_id) => {
                $.ajax({
                    url: `{{ url('student/new-package/get-sub-classroom-category') }}`,
                    type: `GET`,
                    data:{
                        classroom_category_id:classroom_category_id
                    }
                })
                .done(function(res, xhr, meta) {
                    let element = ``;
                    if(res.data.length > 0){
                        $.each(res.data, function(index, data) {
                            if(index == 0){
                                $('#init_class_sub_category').val(data.id);
                                element += `
                                    <div class="mb-2 mr-2">
                                        <button type="button" class="btn btn-primary btn-category btn-pill" data-id="${data.id}">${data.name}</button>
                                    </div>
                                `;

                                getPackageBySubCategory(data.id,2);
                                getSessionVideo(data.id);
                            }
                            else{
                                element += `
                                    <div class="mb-2 mr-2">
                                        <button type="button" class="btn btn-outline-primary btn-category btn-pill" data-id="${data.id}">${data.name}</button>
                                    </div>
                                `;
                            }
                        });
                        $('#category').html(element);
                    }
                    else{
                        $('#category').empty();
                        $('#init_class_sub_category').val(null);
                        getPackageByCategory(classroom_category_id);
                        getSessionVideo('21ef9cb2-dd72-4304-8397-20b17ff35bef');
                    }

                    if(!check_init_filter){
                        check_init_filter = true;
                        $('#filter-2').click();
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            getClassroomCategory = () => {
                $.ajax({
                    url: `{{ url('public/get-classroom-category') }}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    let element = ``;
                    $.each(res.data, function(index, data) {
                        if(index == 0){
                            $('#init_class_category').val(data.id);
                            element += `
                            <li class="splide__slide px-2">
                                <div class="class-category-filter__wrapper class-category-selected" data-id=${data.id}>
                                    <div class="class-category-filter-overlay row-center">
                                        <h4>${data.name}</h4>
                                    </div>
                                    <img src="././assets/images/category-placeholder.png" alt="">
                                </div>
                            </li>
                            `;
                        }
                        else{
                            element += `
                            <li class="splide__slide px-2">
                                <div class="class-category-filter__wrapper" data-id=${data.id}>
                                    <div class="class-category-filter-overlay row-center">
                                        <h4>${data.name}</h4>
                                    </div>
                                    <img src="././assets/images/category-placeholder.png" alt="">
                                </div>
                            </li>
                            `;
                        }
                    });
                    $('.splide__list').html(element);
                    initSplide();
                    getSubCategory($('#init_class_category').val());
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            initPackage = () => {
                $.ajax({
                    url: `{{ url('student/new-package/get-package') }}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    let element = ``;
                    if(res.data.data.length > 0){
                        $.each(res.data.data, function(index, data) {
                            element += `
                            <div class="col-lg-6 mb-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card" style="border: none !important">
                                            <img class="card-img-top rounded" src="${data.image_url}" alt="Card image cap" style="height: 300px !important">
                                            <h5 class="card-title mt-5" id="title-package">${data.classroom_name}</h5>
                                            <div class="card-toolbar">
                                                <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                                    {{-- description --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link active" data-toggle="tab" href="#tab-description-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Text/Article.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5"/>
                                                                        <path d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L12.5,10 C13.3284271,10 14,10.6715729 14,11.5 C14,12.3284271 13.3284271,13 12.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z" fill="#000000" opacity="0.3"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Deskripsi</span>
                                                        </a>
                                                    </li>
                                                    {{-- end description --}}

                                                    {{-- Coach --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-coach-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/User.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>
                                                            <span class="nav-text font-size-lg">Coach</span>
                                                        </a>
                                                    </li>
                                                    {{-- end Coach --}}

                                                    {{-- tools --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-tools-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                                                        <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Tools</span>
                                                        </a>
                                                    </li>
                                                    {{-- end tools --}}
                                                </ul>
                                            </div>
                                            <div class="tab-content" style="height:200px !important">
                                                {{-- description --}}
                                                <div class="tab-pane show active" id="tab-description-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5 overflow-auto" style="height:130px !important">
                                                        ${data.description}
                                                    </div>
                                                    <div class="footer d-flex justify-content-between">
                                                        <div class="pt-3">
                                                            <span> <h4>Rp. ${numeral(data.price).format('0,0')},-</h4> </span>
                                                        </div>
                                                        <div class="pt-3">
                                                            <span>${data.session_total} Sesi </span><span>@${data.session_duration}Menit</span>
                                                        </div>
                                                        `;
                                                        // if(!data.is_buy){
                                                            element +=`
                                                            <div id="${data.classroom_id}">
                                                                <button type="button" class="btn btn-primary btn-registration" data-type="1" data-id="${data.classroom_id}" data-classroom_name="${data.classroom_name}" data-price="${data.price}">
                                                                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                                            <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                            <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                        </g>
                                                                    </svg><!--end::Svg Icon--></span>
                                                                    Daftar Kelas
                                                                </button>
                                                            </div>`;
                                                        // }
                                                element+=`
                                                    </div>
                                                </div>
                                                {{-- end description --}}

                                                {{-- coach --}}
                                                <div class="tab-pane show" id="tab-coach-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5">
                                                        <div style="height:180px !important" class="overflow-auto">
                                                        <div class="row">`;
                            if(data.coach.length > 0){
                                $.each(data.coach, function(index, item) {
                                    element += `
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-5 mb-3">
                                                    <img src="${item.coach_image_url}" class="rounded" width="50" height="50"/>
                                                </div>
                                                <div class="d-flex flex-column font-weight-bold">
                                                    <p class="mb-1 font-size-lg">${item.coach_name}</p>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                            else{
                                element += `
                                        <div class="col-12">
                                            <div class="mb-3 d-flex align-items-center">
                                                <p class="mb-1 font-size-lg text-muted">Coach Not Available</p>
                                            </div>
                                        </div>
                                    `;
                            }
                            element += `
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- end coach --}}

                                                {{-- tools --}}
                                                <div class="tab-pane show" id="tab-tools-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5">

                                                            `;
                            if(data.tools.length >0){
                                element += `<ul>`;
                                $.each(data.tools, function(index, value) {
                                    element += `<li>${value.tool_name}</li>`;
                                });
                                element += `</ul>`;
                            }
                            else{
                                element += `<p class="mb-1 font-size-lg text-muted">Tools Not Available</p>`;
                            }
                            element+=`

                                                    </div>
                                                </div>
                                                {{-- end tools --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                        });

                        let element_paginate = `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 previous-package"><i class="ki ki-bold-arrow-back icon-xs"></i></a>`;
                        let last_page = res.data.last_page;

                        $.each(res.data.links,function(index,data){
                            if(index <= last_page){
                                if(index > 0){
                                    if(index == 1){
                                        element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 active paginate-package">${index}</a>`;
                                    }else{
                                        element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 paginate-package">${index}</a>`;
                                    }
                                }
                            }
                        });
                        element_paginate += `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 next-package"><i class="ki ki-bold-arrow-next icon-xs"></i></a>`;

                        $('#package-paginate').html(element_paginate);
                        $('#conference-package').html(element);
                    }else{
                        element+=`
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-muted text-center">
                                        <h4>Class Not Available</h4>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#package-paginate').empty();
                        $('#conference-package').html(element);
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            getPackageBySubCategory = (sub_classroom_category_id, type_classroom = null) => {
                $.ajax({
                    url: `{{ url('student/new-package/sub-classroom-category') }}/${sub_classroom_category_id}`,
                    type: `GET`,
                    data: {
                        type_classroom : type_classroom
                    }
                })
                .done(function(res, xhr, meta) {
                    let element = ``;
                    if(res.data.data.length >0){
                        $.each(res.data.data, function(index, data) {
                            element += `
                            <div class="col-lg-6 mb-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card" style="border: none !important">
                                            <img class="card-img-top rounded" src="${data.image_url}" alt="Card image cap" style="height: 300px !important">
                                            <h5 class="card-title mt-5" id="title-package">${data.classroom_name}</h5>
                                            <div class="card-toolbar">
                                                <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                                    {{-- description --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link active" data-toggle="tab" href="#tab-description-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Text/Article.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5"/>
                                                                        <path d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L12.5,10 C13.3284271,10 14,10.6715729 14,11.5 C14,12.3284271 13.3284271,13 12.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z" fill="#000000" opacity="0.3"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Deskripsi</span>
                                                        </a>
                                                    </li>
                                                    {{-- end description --}}

                                                    {{-- Coach --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-coach-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/User.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>
                                                            <span class="nav-text font-size-lg">Coach</span>
                                                        </a>
                                                    </li>
                                                    {{-- end Coach --}}

                                                    {{-- tools --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-tools-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                                                        <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Tools</span>
                                                        </a>
                                                    </li>
                                                    {{-- end tools --}}
                                                </ul>
                                            </div>
                                            <div class="tab-content" style="height:200px !important">
                                                {{-- description --}}
                                                <div class="tab-pane show active" id="tab-description-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5 overflow-auto" style="height:130px !important">
                                                        ${data.description}
                                                    </div>
                                                    <div class="footer d-flex justify-content-between">
                                                        <div class="pt-3">
                                                            <span> <h4>Rp. ${numeral(data.price).format('0,0')},-</h4> </span>
                                                        </div>
                                                        <div class="pt-3">
                                                            <span>${data.session_total} Sesi </span><span>@${data.session_duration}Menit</span>
                                                        </div>
                                                        `;
                                                        // if(!data.is_buy){
                                                            element +=`
                                                            <div id="${data.classroom_id}">
                                                                <button type="button" class="btn btn-primary btn-registration" data-type="1" data-id="${data.classroom_id}" data-classroom_name="${data.classroom_name}" data-price="${data.price}">
                                                                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                                            <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                            <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                        </g>
                                                                    </svg><!--end::Svg Icon--></span>
                                                                    Daftar Kelas
                                                                </button>
                                                            </div>`;
                                                        // }
                                                        element+=`
                                                    </div>
                                                </div>
                                                {{-- end description --}}

                                                {{-- coach --}}
                                                <div class="tab-pane show" id="tab-coach-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5 overflow-auto">
                                                        `;
                            if(data.coach.length > 0){
                                $.each(data.coach, function(index, item) {
                                    element += `
                                        <div class="d-flex align-items-center">
                                            <div class="mr-5 mb-3">
                                                <img src="${item.coach_image_url}" class="rounded" width="50" height="50"/>
                                            </div>
                                            <div class="d-flex flex-column font-weight-bold">
                                                <p class="mb-1 font-size-lg">${item.coach_name}</p>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                            else{
                                element += `
                                        <div class="mb-3 d-flex align-items-center">
                                            <p class="mb-1 font-size-lg text-muted">Coach Not Available</p>
                                        </div>
                                    `;
                            }
                            element += `
                                                    </div>
                                                </div>
                                                {{-- end coach --}}

                                                {{-- tools --}}
                                                <div class="tab-pane show" id="tab-tools-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5">

                                                            `;
                            if(data.tools.length >0){
                                element += `<ul>`;
                                $.each(data.tools, function(index, value) {
                                    element += `<li>${value.tool_name}</li>`;
                                });
                                element += `</ul>`;
                            }
                            else{
                                element += `<p class="mb-1 font-size-lg text-muted">Tools Not Available</p>`;
                            }
                            element+=`

                                                    </div>
                                                </div>
                                                {{-- end tools --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                        });

                        let element_paginate = `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 previous-package"><i class="ki ki-bold-arrow-back icon-xs"></i></a>`;
                        let last_page = res.data.last_page;

                        $.each(res.data.links,function(index,data){
                            if(index <= last_page){
                                if(index > 0){
                                    if(index == 1){
                                        element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 active paginate-package">${index}</a>`;
                                    }else{
                                        element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 paginate-package">${index}</a>`;
                                    }
                                }
                            }
                        });
                        element_paginate += `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 next-package"><i class="ki ki-bold-arrow-next icon-xs"></i></a>`;

                        $('#package-paginate').html(element_paginate);
                    }else{
                        element += `
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-muted text-center">
                                        <h4>Class Not Available</h4>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#package-paginate').empty();
                    }
                    $('#conference-package').html(element);
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            getSessionVideo = (id) => {
                $.ajax({
                    url: `{{ url('student/new-package/get-session-video') }}`,
                    type: `GET`,
                    data:{
                        sub_classroom_category_id:id
                    }
                })
                .done(function(res, xhr, meta) {
                    if (res.status == 200) {
                        let element = ``;
                        if(res.data.data.length > 0){
                            $.each(res.data.data, function(index, data) {
                                element += `
                                <div class="card mb-4">
                                    <div width="100%" class="p-5">
                                        <img style="float: left !important;" src="${data.image_url}" class="rounded" width="20%" height="150px">
                                        <div class="row">
                                            <div class="col">
                                                <a href="{{url('student/package-detail')}}/${data.id}" style="color: black;">
                                                    <h4>${data.name}</h4>
                                                </a>
                                                <p>${data.description}</p>
                                                <p>Mentored by ${data.coach_name}</p>
                                                <p>
                                                    <span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                                        </g>
                                                    </svg><!--end::Svg Icon--></span>
                                                    <span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                                        </g>
                                                    </svg><!--end::Svg Icon--></span>
                                                    <span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                                        </g>
                                                    </svg><!--end::Svg Icon--></span>
                                                    <span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                                        </g>
                                                    </svg><!--end::Svg Icon--></span>
                                                    <span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                                        </g>
                                                    </svg><!--end::Svg Icon--></span>

                                                    <span class="text-primary font-size-h5 font-weight-bold"> Rp. ${numeral(data.price).format('0,0')},- </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                            });
                            let element_paginate = `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 previous-video"><i class="ki ki-bold-arrow-back icon-xs"></i></a>`;
                            let last_page = res.data.last_page;

                            $.each(res.data.links,function(index,data){
                                if(index <= last_page){
                                    if(index > 0){
                                        if(index == 1){
                                            element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 active paginate-video">${index}</a>`;
                                        }else{
                                            element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 paginate-video">${index}</a>`;
                                        }
                                    }
                                }
                            });
                            element_paginate += `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 next-video"><i class="ki ki-bold-arrow-next icon-xs"></i></a>`;

                            $('#video-paginate').html(element_paginate);
                        }
                        else{
                            element+=`
                                <div class="card">
                                    <div class="card-body text-muted text-center">
                                        <h4>Video Not Available</h4>
                                    </div>
                                </div>
                            `;
                            $('#video-paginate').empty()
                        }
                        $('.video-course').html(element);
                        KTApp.unblockPage();
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            getPackageByCategory = (classroom_category_id) => {
                KTApp.blockPage({
                    overlayColor: '#000000',
                    state: 'primary',
                    opacity: 0.5,
                    message: 'Processing...'
                });
                $.ajax({
                    url: `{{ url('student/new-package/classroom-category') }}/${classroom_category_id}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    let element = ``;
                    if(res.data.data.length >0){
                        $.each(res.data.data, function(index, data) {
                            element += `
                            <div class="col-lg-6 mb-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card" style="border: none !important">
                                            <img class="card-img-top rounded" src="${data.image_url}" alt="Card image cap" style="height: 300px !important">
                                            <h5 class="card-title mt-5" id="title-package">${data.classroom_name}</h5>
                                            <div class="card-toolbar">
                                                <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                                    {{-- description --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link active" data-toggle="tab" href="#tab-description-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Text/Article.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5"/>
                                                                        <path d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L12.5,10 C13.3284271,10 14,10.6715729 14,11.5 C14,12.3284271 13.3284271,13 12.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z" fill="#000000" opacity="0.3"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Deskripsi</span>
                                                        </a>
                                                    </li>
                                                    {{-- end description --}}

                                                    {{-- Coach --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-coach-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/User.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                                                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>
                                                            <span class="nav-text font-size-lg">Coach</span>
                                                        </a>
                                                    </li>
                                                    {{-- end Coach --}}

                                                    {{-- tools --}}
                                                    <li class="nav-item mr-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab-tools-${data.classroom_id}">
                                                            <span class="nav-icon">
                                                                <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"/>
                                                                        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                                                        <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                                                    </g>
                                                                </svg><!--end::Svg Icon--></span>
                                                            </span>

                                                            <span class="nav-text font-size-lg">Tools</span>
                                                        </a>
                                                    </li>
                                                    {{-- end tools --}}
                                                </ul>
                                            </div>
                                            <div class="tab-content" style="height:200px !important">
                                                {{-- description --}}
                                                <div class="tab-pane show active" id="tab-description-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5 overflow-auto" style="height:130px !important">
                                                        ${data.description}
                                                    </div>
                                                    <div class="footer d-flex justify-content-between">
                                                        <div class="pt-3">
                                                            <span> <h4>Rp. ${numeral(data.price).format('0,0')},-</h4> </span>
                                                        </div>
                                                        <div class="pt-3">
                                                            <span>${data.session_total} Sesi </span><span>@${data.session_duration}Menit</span>
                                                        </div>`;
                                                        // if(!data.is_buy){
                                                        element +=`
                                                            <div id="${data.classroom_id}">
                                                                <button type="button" class="btn btn-primary btn-registration" data-type="1" data-id="${data.classroom_id}" data-classroom_name="${data.classroom_name}" data-price="${data.price}">
                                                                    <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                                            <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                            <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                                        </g>
                                                                    </svg><!--end::Svg Icon--></span>
                                                                    Daftar Kelas
                                                                </button>
                                                            </div>`;
                                                        // }
                                                    element +=`</div>
                                                </div>
                                                {{-- end description --}}

                                                {{-- coach --}}
                                                <div class="tab-pane show" id="tab-coach-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5 overflow-auto">
                                                        `;
                            if(data.coach.length > 0){
                                $.each(data.coach, function(index, item) {
                                    element += `
                                        <div class="d-flex align-items-center">
                                            <div class="mr-5 mb-3">
                                                <img src="${item.coach_image_url}" class="rounded" width="50" height="50"/>
                                            </div>
                                            <div class="d-flex flex-column font-weight-bold">
                                                <p class="mb-1 font-size-lg">${item.coach_name}</p>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                            else{
                                element += `
                                        <div class="mb-3 d-flex align-items-center">
                                            <p class="mb-1 font-size-lg text-muted">Coach Not Available</p>
                                        </div>
                                    `;
                            }
                            element += `
                                                    </div>
                                                </div>
                                                {{-- end coach --}}

                                                {{-- tools --}}
                                                <div class="tab-pane show" id="tab-tools-${data.classroom_id}" role="tabpanel">
                                                    <div class="form-group mt-5">

                                                            `;
                            if(data.tools.length >0){
                                element += `<ul>`;
                                $.each(data.tools, function(index, value) {
                                    element += `<li>${value.tool_name}</li>`;
                                });
                                element += `</ul>`;
                            }
                            else{
                                element += `<p class="mb-1 font-size-lg text-muted">Tools Not Available</p>`;
                            }
                            element+=`

                                                    </div>
                                                </div>
                                                {{-- end tools --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                        });
                        let element_paginate = `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 previous-package"><i class="ki ki-bold-arrow-back icon-xs"></i></a>`;
                        let last_page = res.data.last_page;

                        $.each(res.data.links,function(index,data){
                            if(index <= last_page){
                                if(index > 0){
                                    if(index == 1){
                                        element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 active paginate-package">${index}</a>`;
                                    }else{
                                        element_paginate += `<a href="${data.url}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1 paginate-package">${index}</a>`;
                                    }
                                }
                            }
                        });
                        element_paginate += `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 next-package"><i class="ki ki-bold-arrow-next icon-xs"></i></a>`;

                        $('#package-paginate').html(element_paginate);
                    }else{
                        element += `
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-muted text-center">
                                        <h4>Class Not Available</h4>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    $('#conference-package').html(element);
                    KTApp.unblockPage();
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            paginateVideo = (url) => {
                $.ajax({
                    url: url,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    if (res.status == 200) {
                        let element = ``;
                        if(res.data.data.length > 0){
                            $.each(res.data.data, function(index, data) {
                                element += `
                                <div class="card mb-4">
                                    <div width="100%" class="p-5">
                                        <img style="float: left !important;" src="${data.image_url}" class="rounded" width="20%" height="150px">
                                        <div class="row">
                                            <div class="col">
                                                <a href="{{url('student/package-detail')}}/${data.id}" style="color: black;">
                                                    <h4>${data.name}</h4>
                                                </a>
                                                <p>${data.description}</p>
                                                <p>Mentored by ${data.coach_name}</p>
                                                <p>
                                                    <span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                                        </g>
                                                    </svg><!--end::Svg Icon--></span>
                                                    <span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                                        </g>
                                                    </svg><!--end::Svg Icon--></span>
                                                    <span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                                        </g>
                                                    </svg><!--end::Svg Icon--></span>
                                                    <span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                                        </g>
                                                    </svg><!--end::Svg Icon--></span>
                                                    <span class="svg-icon svg-icon-warning"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                                        </g>
                                                    </svg><!--end::Svg Icon--></span>

                                                    <span class="text-primary font-size-h5 font-weight-bold"> Rp. ${numeral(data.price).format('0,0')},- </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                            });
                        }
                        $('.video-course').html(element);
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

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
