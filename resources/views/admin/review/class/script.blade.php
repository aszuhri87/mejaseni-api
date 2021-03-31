<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table,
                init_select_sub_category,
                init_select_category,
                init_select_rating,
                init_select_package;

            $(document).ready(function() {
                formSubmit();
                initAction();
                // getSubCategory();
                getCategory();
                initTable();
                init_select_sub_category = new SlimSelect({
                    select: '#sub-category'
                });
                init_select_category = new SlimSelect({
                    select: '#category'
                });
                init_select_rating = new SlimSelect({
                    select: '#rating'
                });
                init_select_package = new SlimSelect({
                    select: '#package'
                });
                $('.dropdown-menu').on('click', function(event) {
                    event.stopPropagation();
                });
            });

            const initTable = (category,sub_category,package,rating) => {
                init_table = $('#init-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    sScrollY: ($(window).height() < 700) ? $(window).height() - 200 : $(window).height() - 450,
                    ajax: {
                        type: 'POST',
                        url: "{{ url('admin/report/review/class/dt') }}",
                        data:{
                            category:category,
                            sub_category:sub_category,
                            package:package,
                            rating:rating,
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'name' },
                        { data: 'sub_classroom_category_name' },
                        { data: 'total_order' },
                        { data: 'rating' },
                        { defaultContent: '' }
                        ],
                    columnDefs: [
                        {
                            targets: 0,
                            searchable: false,
                            orderable: false,
                            className: "text-center",
                        },
                        {
                            targets: 1,
                            searchable: false,
                            orderable: false,
                            className: "text-left",
                            data:"name",
                            render: function(data, type, full, meta){
                                return `
                                    <div class="d-flex">
                                        <img src="${full.image}" class="rounded" width="50px" height="50px">
                                        <div class="ml-3">
                                            <h5>${data}</h5>
                                            <span class="text-muted">${full.package_type}</span>
                                        </div>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: 2,
                            searchable: true,
                            orderable: true,
                            className: "text-left",
                            data: "classroom_category_name",
                            render: function(data, type, full, meta){
                                return `<strong>${data}</strong><br><span class="text-muted">${full.classroom_category_name}</span>`;
                            }
                        },
                        {
                            targets: 3,
                            searchable: true,
                            orderable: true,
                            className: "text-center",
                        },
                        {
                            targets: -2,
                            searchable: true,
                            orderable: true,
                            className: "text-center",
                            data: "rating",
                            render: function(data, type, full, meta){
                                let deficiency = 5-parseInt(data);
                                let element = ``;
                                for(let i=0; i<data; i++){
                                    element +=`
                                    <span class="svg-icon svg-icon-warning svg-icon-sm"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                        </g>
                                    </svg><!--end::Svg Icon--></span>
                                    `;
                                }
                                for(let i=0; i<deficiency; i++){
                                    element += `
                                    <span class="svg-icon text-secondary svg-icon-sm"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                            <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                        </g>
                                    </svg><!--end::Svg Icon--></span>
                                    `;
                                }
                                return element;
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
                                    <a href="{{url('admin/report/review/class')}}/${data}" title="Lihat" class="btn btn-sm btn-clean btn-icon mr-2">
                                        <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Visible.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
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
                $(document).on('change','#category',function(event){
                    category = $(this).val();
                    getSubCategory($(this).val());
                });

                $(document).on('click','#btn-reset',function(event){
                    $('#category').val('').change();
                    $('#sub-category').val('').change();
                    $('#rating').val('').change();
                    $('#package').val('').change();
                });
            },
            formSubmit = () => {
                $('#form-filter').submit(function(event){
                    event.preventDefault();
                    let category = $('#category').val();
                    let sub_category = $('#sub-category').val();
                    let package = $('#package').val();
                    let rating = $('#rating').val();

                    let text = $('.btn-loading-basic').html();
                    btn_loading_basic('start',text);
                    initTable(category,sub_category,package,rating);
                    btn_loading_basic('stop',text);
                });
            },
            getSubCategory = (category_id) => {
                let url = '';
                if(category_id){
                    url = `{{url('public/get-sub-classroom-category-by-category')}}/${category_id}`;
                }
                else{
                    url = `{{ url('public/get-sub-classroom-category') }}`;
                }
                $.ajax({
                    url: url,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Semua</option>`;
                    $.each(res.data, function(index, data){
                        element += `
                            <option value="${data.id}">${data.name}</option>
                        `;
                    })
                    $('#sub-category').html(element);

                    if(init_select_sub_category){
                        init_select_sub_category.destroy();
                    }
                    init_select_sub_category = new SlimSelect({
                        select:'#sub-category'
                    });
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                    btn_loading('stop')
                });
            },
            getCategory = () => {
                $.ajax({
                    url: `{{ url('public/get-classroom-category') }}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="">Semua</option>`;
                    $.each(res.data, function(index, data){
                        element += `
                            <option value="${data.id}">${data.name}</option>
                        `;
                    })
                    $('#category').html(element);

                    if(init_select_category){
                        init_select_category.destroy();
                    }
                    init_select_category = new SlimSelect({
                        select:'#category'
                    });
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                    btn_loading('stop')
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
