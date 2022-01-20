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
                        type: 'POST',
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
                                if(full.expired){
                                    return `<span class="label label-secondary label-pill label-inline mr-2">Exxpired</span>`
                                }else if(data == 2){
                                    return `<span class="label label-success label-pill label-inline mr-2">Success</span>`;
                                }else if(data == 1){
                                    return `<span class="label label-warning label-pill label-inline mr-2">Waiting</span>`;
                                }else{
                                    return `<span class="label label-danger label-pill label-inline mr-2">Cancel</span>`;
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
                                if(full.expired){
                                    return `<span class="label label-secondary label-pill label-inline mr-2">Exxpired</span>`
                                }else if(full.status == 0){
                                    return `<span class="label label-danger label-pill label-inline mr-2">Dibatalkan</span>`;
                                }else if(data){
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
                                if(full.status == 1 && !full.expired){
                                    return `
                                        <a href="{{ url('/waiting-payment') }}/${data}" title="Lanjutkan Pembayaran" target="_blank" data-toogle="tooltip" class="btn btn-primary btn-sm mr-2">
                                            Pembayaran
                                        </a>
                                    `;
                                }else{
                                    return `
                                        <a href="{{ url('student/invoice/detail') }}/${data}" title="Lihat Detail" data-toogle="tooltip" class="btn btn-detail ${full.latest ? 'btn-danger' : 'btn-outline-primary'} btn-sm mr-2">
                                            Lihat Pembelian Saya
                                        </a>
                                    `;
                                }
                            }
                        },
                    ],
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
                                total += parseInt(data.price);

                                element += `
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="">`;

                                        if(data.classroom_id){
                                            element += `<span class="pt-2">${data.classroom_name} (Rp. ${numeral(data.price).format('0,0')})</span>`;
                                        }else if(data.master_lesson_id){
                                            element += `<span class="pt-2">${data.master_lesson_name} (Rp. ${numeral(data.price).format('0,0')})</span>`;
                                        }else if(data.theory_id){
                                            element += `<span class="pt-2">${data.theory_name} (Rp. ${numeral(data.price).format('0,0')})</span>`;
                                        }else if(data.event_id){
                                            element += `<span class="pt-2">${data.event_name} (Rp. ${numeral(data.price).format('0,0')})</span>`;
                                        }else{
                                            element += `<span class="pt-2">${data.session_video_name} (Rp. ${numeral(data.price).format('0,0')})</span>`;
                                        }

                                element += `
                                        </div>
                                            <div class="text-right">`;

                                            if(data.classroom_id){
                                                element += `<a class="btn btn-primary" href="{{ url('student/my-class') }}">Go To My Class</a>`;
                                            }else if(data.master_lesson_id){
                                                element += `<a class="btn btn-primary" href="{{ url('student/my-class') }}">Go To My Class</a>`;
                                            }else if(data.theory_id){
                                                element += `<a class="btn btn-primary" href="{{ url('student/my-class') }}">Go To My Class</a>`;
                                            }else if(data.event_id){
                                                element += `<a class="btn btn-primary" href="{{ url('student/my-class') }}">Go To My Class</a>`;
                                            }else{
                                                element += `<a class="btn btn-primary" href="{{ url('student/my-video') }}">Go To My Video</a>`;
                                            }

                                element += `</div>
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
