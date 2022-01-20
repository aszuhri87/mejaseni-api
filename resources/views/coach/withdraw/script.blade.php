<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, init_profile_coach_video;

            $(document).ready(function() {
                initTable();
            });

            const initTable = () => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('coach/withdraw/detail/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'datetime' },
                        { data: 'bank' },
                        { data: 'name_account' },
                        { data: 'total' },
                        { data: 'status_text' },
                        { data: 'image' },
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
                                return `${moment(data).format('DD MMMM YYYY')}`;
                            }
                        },
                        {
                            targets: 2,
                            data:"bank",
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
                            targets: 4,
                            data:"total",
                            render: function(data, type, full, meta){
                                return 'Rp. ' + numeral(data).format('0,0');
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
