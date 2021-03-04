<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            let init_table;

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
                        url: "{{ url('coach/notification/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        // { data: 'DT_RowIndex' },
                        { data: 'text' },
                        // { data: 'DT_RowIndex' },
                        // { data: 'DT_RowIndex' },
                        { data: 'datetime' },
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
                            orderable: false,
                            className: "text-center",
                            data:"datetime",
                            render : function(data, type, full, meta) {
                                const created = moment(full.datetime).format("DD MMMM YYYY - HH:MM:ss");

                                return created
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
                                    <a class="btn btn-sm btn-permission mr-1 btn-clean btn-icon btn-detail" title="Detail" href="">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Notification2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000"/>
                                                <circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
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
                
                $(document).on('click', '.btn-detail', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('.class-name').html(data.class_name);
                    $('.coach-name').html(data.coach_name);
                    $('.student-name').html(data.student_name);
                    $('.number').html(data.number);
                    $('.payment-chanel').html(data.payment_chanel);
                    $('.total').html(data.total);
                    $('.text').html(data.text);
                    $('.datetime').html(moment(data.datetime).format('DD MMMM YYYY HH:MM:ss'));

                    showModal('modal-notification-detail');
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
