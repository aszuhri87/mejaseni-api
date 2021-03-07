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
                        { data: 'datetime' },
                        { data: 'type' },
                        { data: 'text' },
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
                            data:"datetime",
                            render : function(data, type, full, meta) {
                                return moment(data).format("DD MMMM YYYY - HH:MM:ss");
                            }
                        },
                        {
                            targets: 2,
                            searchable: false,
                            orderable: false,
                            className: "text-left",
                            data: "type",
                            render: function(data, type, full, meta){
                                if(data == 1){
                                    return `Transaction Success`;
                                }else if(data == 2){
                                    return `Schedule Confirmed`;
                                }else{
                                    return `Reschedule`;
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
