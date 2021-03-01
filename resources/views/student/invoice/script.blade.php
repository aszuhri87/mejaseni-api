<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table;

            $(document).ready(function() {
                initTable();
                initAction();
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'get',
                        url: "{{ url('student/invoice/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'datetime' },
                        { data: 'number' },
                        { data: 'total' },
                        { data: 'status' },
                        { defaultContent: 'confirmed' },
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
                            searchable: true,
                            orderable: true,
                            className: "text-left",
                            data:"datetime",
                            render: function(data, type, full, meta){
                                return `${moment(data).format('DD MMMM YYYY')}`;
                            }
                        },
                        {
                            targets: 3,
                            searchable: true,
                            orderable: true,
                            className: "text-left",
                            data:"total",
                            render: function(data, type, full, meta){
                                return `<strong>Rp. ${numeral(data).format('0,0')}</strong>`;
                            }
                        },
                        {
                            targets: -3,
                            searchable: true,
                            orderable: true,
                            className: "text-center",
                            data:"status",
                            render: function(data, type, full, meta){
                                if(data == 2){
                                    return `<span class="label label-success label-pill label-inline mr-2">Success</span>`;
                                }
                                else if(data == 1){
                                    return `<span class="label label-warning label-pill label-inline mr-2">Waiting</span>`;
                                }else{
                                    return `<span class="label label-danger label-pill label-inline mr-2">Cancel</span>`
                                }
                            }
                        },
                        {
                            targets: -2,
                            searchable: true,
                            orderable: true,
                            className: "text-center",
                            data:"confirmed",
                            render: function(data, type, full, meta){
                                if(data){
                                    return `<span class="label label-success label-pill label-inline mr-2">Dikonfirmasi</span>`;
                                }
                                else{
                                    return `<span class="label label-warning label-pill label-inline mr-2">Belum Dikonfirmasi</span>`
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
                                        <a href="{{ url('/waiting-payment') }}/${data}" title="Lanjutkan Pembayaran" data-toogle="tooltip" class="btn btn-primary btn-sm mr-2">
                                            Pembayaran
                                        </a>
                                        `;
                                }else{
                                    return `
                                        <a href="{{ url('student/invoice/detail') }}/${data}" title="Lihat Detail" data-toogle="tooltip" class="btn btn-detail btn-outline-primary btn-sm mr-2">
                                            <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Visible.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                    <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                                </g>
                                            </svg><!--end::Svg Icon--></span>
                                            Detail
                                        </a>
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
                $(document).on('click','.btn-detail',function(event){
                    event.preventDefault();

                    $.ajax({
                        url: $(this).attr('href'),
                        type: 'GET',
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            let element = ``;
                            $('#total').empty();
                            let total = 0;
                            $.each(res.data, function(index, data){
                                total+=data.price;

                                element += `
                                    <div class="row">
                                        <div class="col-6">
                                        `;
                                        if(data.classroom_id){
                                            element += `<span>${data.classroom_name}</span>`;
                                        }
                                        else if(data.master_lesson_id){
                                            element += `<span>${data.master_lesson_name}</span>`;
                                        }
                                        else if(data.theory_id){
                                            element += `<span>${data.theory_name}</span>`;
                                        }else{
                                            element += `<span>${data.session_video_name}</span>`;
                                        }
                                        element += `

                                        </div>
                                        <div class="col-6 text-right">
                                            <h5>Rp. 500,000</h5>
                                        </div>
                                    </div>
                                    <hr>
                                `;
                            })
                            $('#detail').html(element);
                            $('#total').html(`Rp. ${numeral(total).format('0,0')}`);
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {

                    });
                    showModal('modal-invoice');
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
