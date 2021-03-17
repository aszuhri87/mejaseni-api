<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, init_participants_table;
            var init_classroom_category;

            $(document).ready(function() {
                initTable();
                formSubmit();
                initAction();
                initDateRangePicker()
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/event/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'image_url' },
                        { data: 'title' },
                        { data: 'start_at' },
                        { data: 'end_at' },
                        { data: 'quota' },
                        { data: 'count_participants' },
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
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                            data: "image_url",
                            render : function(data, type, full, meta) {
                                return `
                                    <img src="${data}" class="w-75 align-self-end" alt="">
                                    `
                            }
                        },
                        {
                            targets: -5,
                            searchable: false,
                            className: "text-center",
                            data: "start_at",
                            render : function(data, type, full, meta) {
                                return moment(data).format('D MMMM YYYY, HH:mm')
                            }
                        },
                        {
                            targets: -4,
                            searchable: false,
                            className: "text-center",
                            data: "end_at",
                            render : function(data, type, full, meta) {
                                return moment(data).format('D MMMM YYYY, HH:mm')
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
                                    <a href="{{ url('admin/event') }}/${data}/participants/dt" title="List Participant" class="btn btn-participants btn-sm btn-clean btn-icon mr-2" title="Edit details">
                                        <span class="svg-icon svg-icon-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" fill="#000000"/>
                                                    <path d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" fill="#000000" opacity="0.3"/>
                                                </g>
                                            </svg>
                                        </span>
                                    </a>
                                    <a href="{{url('/admin/event/update')}}/${data}" title="Edit" class="btn btn-edit btn-sm btn-clean btn-icon mr-2" title="Edit details">
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
                                    <a href="{{url('/admin/event')}}/${data}" title="Delete" class="btn btn-delete btn-sm btn-clean btn-icon" title="Delete">
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
            initParticipantsTable = (url) => {
                init_participants_table = $('#init-participants-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: url,
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'image_url' },
                        { data: 'name' },
                        { data: 'phone' },
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
                            searchable: false,
                            orderable: false,
                            data: "image_url",
                            render : function(data, type, full, meta) {
                                return `<img src="${data}" class="viewer-img rounded" width="50" height="50">`
                            }
                        },
                        {
                            targets: -1,
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                            data: "cart_id",
                            render : function(data, type, full, meta) {
                                return `
                                    <a href="{{url('/admin/cart')}}/${data}" title="Delete" class="btn btn-participants-delete btn-sm btn-clean btn-icon" title="Delete">
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

                $('#searchParticipants').keyup(searchDelay(function(event) {
                    init_participants_table.search($(this).val()).draw()
                }, 1000));

                $('#pageLength').on('change', function () {
                    init_participants_table.page.len(this.value).draw();
                });
            },
            initAction = () => {
                $(document).on('click', '#add-btn', function(event){
                    event.preventDefault();

                    $('#form-event').trigger("reset");
                    $('#form-event').attr('action','{{url('admin/event')}}');
                    $('#form-event').attr('method','POST');

                    get_classroom_category();
                    $('.required-date-range').hide();

                    $('#image').html('<input type="file" name="image" class="dropify image"/>');
                    $('.dropify').dropify();

                    showModal('modal-event');
                });


                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    let start_at = moment(data.start_at).format('D MMMM YYYY h:mm A')
                    let end_at = moment(data.end_at).format('D MMMM YYYY h:mm A')

                    let date = `${start_at} - ${end_at}`

                    $('#form-event').trigger("reset");
                    $('#form-event').attr('action', $(this).attr('href'));
                    $('#form-event').attr('method','POST');
                    get_classroom_category(data.classroom_category_id)
                    $('#form-event').find('input[name="title"]').val(data.title);
                    $('#form-event').find('input[name="date"]').val(date);
                    $('#form-event').find('input[name="quota"]').val(data.quota);
                    $('#form-event input[name="is_free"]').prop('checked', data.is_free);
                    $('#form-event').find('textarea[name="location"]').val(data.location);
                    $('#form-event').find('textarea[name="description"]').val(data.description);

                    if(!data.is_free){
                        $("#form-event input[name='total']").removeAttr('disabled')
                        $("#form-event input[name='total']").attr("min","5000")
                        $("#form-event input[name='total']").val(data.total)
                    }
                    $('#image').empty();

                    if(data.image_url){
                        element = `<input type="file" name="image" class="dropify image"  data-default-file="${data.image_url}"/>`;
                    }else{
                        element = `<input type="file" name="image" class="dropify image"/>`;
                    }

                    $('#image').html(element);

                    $('.dropify').dropify();

                    showModal('modal-event');
                });


                $(document).on('click', '.btn-delete', function(event){
                    event.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Delete Event?',
                        text: "Deleted Event will be permanently lost!",
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

                $("#form-event input[name='is_free']").on('change',function(event){
                    event.preventDefault()
                    if($(this).prop('checked')){
                        $("#form-event input[name='total']").attr("min","0")
                        $("#form-event input[name='total']").val(0)
                        $("#form-event input[name='total']").attr("disabled","disabled")
                    }
                    else{
                        $("#form-event input[name='total']").removeAttr('disabled')
                        $("#form-event input[name='total']").attr("min","5000")
                    }
                })

                 $(document).on('click', '.btn-participants', function(event){
                    event.preventDefault()
                    var url = $(this).attr('href');
                    initParticipantsTable(url)


                    showModal('modal-participants')
                })

                 $(document).on('click', '.btn-participants-delete', function(event){
                    event.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Delete Participant?',
                        text: "Deleted Participant will be permanently lost!",
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
                                init_participants_table.draw(false);
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
                $('#form-event').submit(function(event){
                    event.preventDefault();

                    if($('#classroom-category').val() == ""){
                        $('.required-classroom-category').show();
                        return false;
                    }

                    if($('#date-range').val() == ""){
                        $('.required-date-range').show();
                        return false;
                    }

                    let data = new FormData(this)
                    let date = $("#form-event").find('input[name=date]').val()

                    date = date.split('-')
                    let start_at = moment(date[0]).format('YYYY-MM-DD HH:mm:ss')
                    let end_at = moment(date[1]).format('YYYY-MM-DD HH:mm:ss')
                    data.append('start_at', start_at)
                    data.append('end_at', end_at)


                    btn_loading('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: data,
                        contentType: false,
                        cache: false,
                        processData: false,
                    })
                    .done(function(res, xhr, meta) {
                        toastr.success(res.message, 'Success');
                        init_table.draw(false);
                        hideModal('modal-event');
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
            },
            initDateRangePicker = ()=>{
                $('.select_daterange').daterangepicker({
                    buttonClasses: ' btn',
                    applyClass: 'btn-primary',
                    cancelClass: 'btn-secondary',
                    minDate: new Date(),
                    timePicker: true,
                    timePickerIncrement: 30,
                    locale: {
                        format: 'D MMMM YYYY h:mm A'
                    }
                }, function(start, end, label) {
                    $('.form-control.select_dateranges').val( start.format('D MMMM YYYY h:mm A') + ' / ' + end.format('D MMMM YYYY h:mm A'));
                });
            },
            get_classroom_category = (id) => {
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
