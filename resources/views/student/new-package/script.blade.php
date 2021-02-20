<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table;

            $(document).ready(function() {
                // initTable();
                // formSubmit();
                initAction();
                getCategory();
                initPackage();
            });

            const initAction = () => {
                $(document).on('click', '#add-btn', function(event){
                    event.preventDefault();

                    $('#form-invoice').trigger("reset");
                    $('#form-invoice').attr('action','{{url('admin/master/courses/classroom-category')}}');
                    $('#form-invoice').attr('method','POST');

                    showModal('modal-invoice');
                });

                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-invoice').trigger("reset");
                    $('#form-invoice').attr('action', $(this).attr('href'));
                    $('#form-invoice').attr('method','PUT');

                    $('#form-invoice').find('input[name="name"]').val(data.name);

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
            },
            formSubmit = () => {
                $('#form-invoice').submit(function(event){
                    event.preventDefault();

                    btn_loading('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        toastr.success(res.message, 'Success')
                        init_table.draw(false);
                        hideModal('modal-invoice');
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
            },
            getCategory = () => {
                $.ajax({
                    url: `{{ url('public/get-classroom-category') }}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    let element = ``;
                    $.each(res.data, function(index, data) {
                        element += `
                            <div class="mr-2">
                                <button type="button" class="btn btn-outline-primary btn-category" data-id="${data.id}">${data.name}</button>
                            </div>
                        `;
                    });
                    $('#category').html(element);
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
                    $.each(res.data, function(index, data) {
                        element += `
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card" style="border: none !important">
                                        <img class="card-img-top rounded" src="${data.image_url}" alt="Card image cap">
                                        <h5 class="card-title mt-5" id="title-package">${data.classroom_name}</h5>
                                        <div class="card-toolbar">
                                            <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                                {{-- description --}}
                                                <li class="nav-item mr-3">
                                                    <a class="nav-link active" data-toggle="tab" href="#tab-description-${data.coach_schedule_id}">
                                                        <span class="nav-icon">
                                                            <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Clothes/Crown.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                                    <path d="M11.2600599,5.81393408 L2,16 L22,16 L12.7399401,5.81393408 C12.3684331,5.40527646 11.7359848,5.37515988 11.3273272,5.7466668 C11.3038503,5.7680094 11.2814025,5.79045722 11.2600599,5.81393408 Z" fill="#000000" opacity="0.3"/>
                                                                    <path d="M12.0056789,15.7116802 L20.2805786,6.85290308 C20.6575758,6.44930487 21.2903735,6.42774054 21.6939717,6.8047378 C21.8964274,6.9938498 22.0113578,7.25847607 22.0113578,7.535517 L22.0113578,20 L16.0113578,20 L2,20 L2,7.535517 C2,7.25847607 2.11493033,6.9938498 2.31738608,6.8047378 C2.72098429,6.42774054 3.35378194,6.44930487 3.7307792,6.85290308 L12.0056789,15.7116802 Z" fill="#000000"/>
                                                                </g>
                                                            </svg><!--end::Svg Icon--></span>
                                                        </span>

                                                        <span class="nav-text font-size-lg">Deskripsi</span>
                                                    </a>
                                                </li>
                                                {{-- end description --}}

                                                {{-- Coach --}}
                                                <li class="nav-item mr-3">
                                                    <a class="nav-link" data-toggle="tab" href="#tab-coach-${data.coach_schedule_id}">
                                                        <span class="nav-icon">
                                                            <span class="svg-icon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                                        <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"></path>
                                                                        <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"></path>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                        <span class="nav-text font-size-lg">Coach</span>
                                                    </a>
                                                </li>
                                                {{-- end Coach --}}

                                                {{-- tools --}}
                                                <li class="nav-item mr-3">
                                                    <a class="nav-link" data-toggle="tab" href="#tab-tools-${data.coach_schedule_id}">
                                                        <span class="nav-icon">
                                                            <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Clothes/Crown.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                                    <path d="M11.2600599,5.81393408 L2,16 L22,16 L12.7399401,5.81393408 C12.3684331,5.40527646 11.7359848,5.37515988 11.3273272,5.7466668 C11.3038503,5.7680094 11.2814025,5.79045722 11.2600599,5.81393408 Z" fill="#000000" opacity="0.3"/>
                                                                    <path d="M12.0056789,15.7116802 L20.2805786,6.85290308 C20.6575758,6.44930487 21.2903735,6.42774054 21.6939717,6.8047378 C21.8964274,6.9938498 22.0113578,7.25847607 22.0113578,7.535517 L22.0113578,20 L16.0113578,20 L2,20 L2,7.535517 C2,7.25847607 2.11493033,6.9938498 2.31738608,6.8047378 C2.72098429,6.42774054 3.35378194,6.44930487 3.7307792,6.85290308 L12.0056789,15.7116802 Z" fill="#000000"/>
                                                                </g>
                                                            </svg><!--end::Svg Icon--></span>
                                                        </span>

                                                        <span class="nav-text font-size-lg">Tools</span>
                                                    </a>
                                                </li>
                                                {{-- end tools --}}
                                            </ul>
                                        </div>
                                        <div class="tab-content" >
                                            {{-- description --}}
                                            <div class="tab-pane show active" id="tab-description-${data.coach_schedule_id}" role="tabpanel">
                                                <div class="form-group mt-5">
                                                    ${data.description}
                                                </div>
                                                <div class="footer d-flex justify-content-between">
                                                    <div class="pt-3">
                                                        <span> <h4>Rp ${numeral(data.price).format('0,0')},-</h4> </span>
                                                    </div>
                                                    <div class="pt-3">
                                                        <span>${data.session_total} Sesi </span><span>@${data.session_duration}</span>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-primary">Daftar Kelas</button>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end description --}}

                                            {{-- coach --}}
                                            <div class="tab-pane show" id="tab-coach-${data.coach_schedule_id}" role="tabpanel">
                                                <div class="form-group mt-5">
                                                    ${data.coach_name}
                                                </div>
                                            </div>
                                            {{-- end coach --}}

                                            {{-- tools --}}
                                            <div class="tab-pane show" id="tab-tools-${data.coach_schedule_id}" role="tabpanel">
                                                <div class="form-group mt-5">
                                                    <ul>
                                                        `;

                        $.each(data.tools, function(index, value) {
                            element += `<li>${value.tool_name}</li>`;
                        });
                        element+=`
                                                    </ul>
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
