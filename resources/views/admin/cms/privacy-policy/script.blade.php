<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, init_privacy_policy;

            $(document).ready(function() {
                initTable();
                formSubmit();
                initAction();
                
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/cms/privacy-policy/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'description' },
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
                                    <a href="#" title="Add Policy" class="btn btn-policy btn-sm btn-clean btn-icon mr-2" title="Edit details">
                                        <span class="svg-icon svg-icon-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>
                                                    <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>
                                                    <rect fill="#000000" opacity="0.3" x="10" y="9" width="7" height="2" rx="1"/>
                                                    <rect fill="#000000" opacity="0.3" x="7" y="9" width="2" height="2" rx="1"/>
                                                    <rect fill="#000000" opacity="0.3" x="7" y="13" width="2" height="2" rx="1"/>
                                                    <rect fill="#000000" opacity="0.3" x="10" y="13" width="7" height="2" rx="1"/>
                                                    <rect fill="#000000" opacity="0.3" x="7" y="17" width="2" height="2" rx="1"/>
                                                    <rect fill="#000000" opacity="0.3" x="10" y="17" width="7" height="2" rx="1"/>
                                                </g>
                                            </svg>
                                        </span>
                                    </a>
                                    <a href="{{url('/admin/cms/privacy-policy')}}/${data}" title="Edit" class="btn btn-edit btn-sm btn-clean btn-icon mr-2" title="Edit details">
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
            initPrivacyPolicyTable = () => {
                init_privacy_policy = $('#init-privacy-policy-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/cms/privacy-policy-item/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'title' },
                        { data: 'description' },
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
                                    <a href="{{url('/admin/cms/privacy-policy-item')}}/${data}" title="Edit" class="btn btn-edit-privacy-policy btn-sm btn-clean btn-icon mr-2" title="Edit details">
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
                                    <a href="{{url('/admin/cms/privacy-policy')}}/${data}" title="Delete" class="btn btn-delete-privacy-policy btn-sm btn-clean btn-icon" title="Delete">
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
            },
            initAction = () => {
                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-policy').trigger("reset");
                    $('#form-policy').attr('action', $(this).attr('href'));
                    $('#form-policy').attr('method','PUT');

                    $('#form-policy').find('textarea[name="description"]').val(data.description);

                    showModal('modal-policy');
                });


                $(document).on('click','.btn-policy', function(event){
                    event.preventDefault()
                    initPrivacyPolicyTable()
                    showModal('modal-data-privacy-policy')
                })

                $(document).on('click','#add-privacy-policy', function(event){
                    event.preventDefault()

                    $('#form-privacy-policy').trigger("reset");
                    $('#form-privacy-policy').attr('action', '{{ url('admin/cms/privacy-policy') }}');
                    $('#form-privacy-policy').attr('method','POST');

                    showModal('modal-privacy-policy')
                })

                $(document).on('click', '.btn-edit-privacy-policy', function(event){
                    event.preventDefault();

                    var data = init_privacy_policy.row($(this).parents('tr')).data();

                    $('#form-privacy-policy').trigger("reset");
                    $('#form-privacy-policy').attr('action', $(this).attr('href'));
                    $('#form-privacy-policy').attr('method','PUT');

                    $('#form-privacy-policy').find('input[name="title"]').val(data.title);;
                    $('#form-privacy-policy').find('textarea[name="description"]').val(data.description);

                    showModal('modal-privacy-policy');
                });

                $(document).on('click', '.btn-delete-privacy-policy', function(event){
                    event.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Delete Privacy Policy?',
                        text: "Deleted Privacy Policy will be permanently lost!",
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
                                init_privacy_policy.draw(false);
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
                $('#form-policy').submit(function(event){
                    event.preventDefault();

                    btn_loading('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize()
                    })
                    .done(function(res, xhr, meta) {
                        toastr.success(res.message, 'Success');
                        init_table.draw(false);
                        hideModal('modal-policy');
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });


                $('#form-privacy-policy').submit(function(event){
                    event.preventDefault();

                    btn_loading('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize()
                    })
                    .done(function(res, xhr, meta) {
                        toastr.success(res.message, 'Success');
                        init_privacy_policy.draw(false);
                        hideModal('modal-privacy-policy');
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
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
