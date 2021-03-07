<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table;

            $(document).ready(function() {
                initTable();
                initAction();
                formSubmit();
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/transaction/coach/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'datetime' },
                        { data: 'bank' },
                        { data: 'name_account' },
                        { data: 'total' },
                        { data: 'status_text' },
                        { data: 'image' },
                        { defaultContent: '' },
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
                            data:"datetime",
                            render: function(data, type, full, meta){
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">${full.number}</p>
                                        <span class="text-muted">${moment(data).format('DD MMMM YYYY')}</span>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: 2,
                            data:"name_account",
                            render: function(data, type, full, meta){
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">${data}</p>
                                        <span class="text-muted">${full.bank_number}</span>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: 3,
                            data:"bank",
                            render: function(data, type, full, meta){
                                return `
                                    <div class="d-flex flex-column font-weight-bold">
                                        <p class="mb-1 font-size-lg">${full.coach}</p>
                                        <span class="text-muted">${data}</span>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: 4,
                            searchable: true,
                            orderable: true,
                            className: "text-left",
                            data:"total",
                            render: function(data, type, full, meta){
                                return `<strong>Rp. ${numeral(data).format('0,0')}</strong>`;
                            }
                        },
                        {
                            targets: 5,
                            data:"status_text",
                            render: function(data, type, full, meta){
                                if(data == 'Waiting'){
                                    return `
                                        <div class="d-flex flex-column font-weight-bold">
                                            <p class="mb-1 font-size-lg text-warning">${data}</p>
                                        </div>
                                    `;
                                }else if(data == 'Success'){
                                    return `
                                        <div class="d-flex flex-column font-weight-bold">
                                            <p class="mb-1 font-size-lg text-success">${data}</p>
                                        </div>
                                    `;
                                }else{
                                    return `
                                        <div class="d-flex flex-column font-weight-bold">
                                            <p class="mb-1 font-size-lg text-danger">${data}</p>
                                        </div>
                                    `;
                                }
                            }
                        },
                        {
                            targets: 6,
                            data:"image",
                            render: function(data, type, full, meta){
                                if(data){
                                    return `
                                    <div class="symbol symbol-40 symbol-light-success mr-5">
                                        <span class="symbol-label">
                                            <img src="${full.image_url}" width="40" height="40" class="align-self-center rounded" alt=""/>
                                        </span>
                                    </div>
                                    `
                                }else{
                                    return '-';
                                }
                            }
                        },
                        {
                            targets: -1,
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                            data: "id",
                            render : function(data, type, full, meta) {
                                if(full.status == 1){
                                    return `
                                        <a href="{{ url('/waiting-payment') }}/${data}" title="Konfirmasi" data-toogle="tooltip" class="btn btn-confirm btn-primary btn-sm">
                                            Konfirmasi
                                        </a>
                                    `;
                                }else{
                                    return `
                                        <button class="btn btn-success btn-sm" disabled>
                                            Success
                                        </button>
                                    `;
                                }
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
                $(document).on('click','.btn-confirm',function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-confirm').trigger("reset");
                    $('#form-confirm').attr('action', "{{url('admin/transaction/coach/confirm')}}/"+data.id);
                    $('#form-confirm').attr('method','POST');

                    $('#image').html('<input type="file" name="image" class="dropify image"/>');

                    $('.dropify').dropify();

                    $('.coach-name-place').html(data.coach)
                    $('.name-account-place').html(data.name_account)
                    $('.bank-place').html(data.bank)
                    $('.bank-number-place').html(data.bank_number)
                    $('.total-place').html(data.total)
                    $('.date-place').html(data.datetime)

                    showModal('modal-confirm');
                })
            },
            formSubmit = () => {
                $('#form-confirm').submit(function(event){
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
                        hideModal('modal-confirm');
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
