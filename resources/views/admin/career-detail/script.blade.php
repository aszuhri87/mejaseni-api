<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, init_profile_coach_video;

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
                        url: "{{ url('admin/career-detail/dt') }}",
                    },
                    columns: [
                        { data: 'DT_RowIndex' },
                        { data: 'date' },
                        { data: 'name' },
                        { data: 'email' },
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
                                    <a href="{{url('/admin/career-detail')}}/${data}" title="Detail" class="btn btn-detail btn-sm btn-clean btn-icon" title="Edit details">
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

                    $('.name-place').text(data.name);
                    $('.email-place').text(data.email);
                    $('.date-place').text(data.date);

                    $.ajax({
                        url: "{{url('admin/career-detail')}}/" + data.id,
                        type: 'GET',
                        dataType: 'json',
                    })
                    .done(function(res, xhr, meta) {
                        $('.media-content').empty();

                        $.each(res.data, function(index, media) {
                            let extention = media.url.split('.').pop();
                            let icon;

                            if(extention == 'pdf'){
                                icon = 'pdf.png';
                            }else if(extention == 'png'|| extention == 'jpeg'){
                                icon = 'img.png';
                            }else if(extention == 'mp4'|| extention == '3gp'){
                                icon = 'video.png';
                            }else if(extention == 'doc'){
                                icon = 'doc.png';
                            }else if(extention == 'xlsx'){
                                icon = 'xls.png';
                            }else if(extention == 'ppt'){
                                icon = 'ppt.png';
                            }else{
                                icon = 'file.png';
                            }

                            $(`<a href="${media.file_url}" target="_blank" class="media border rounded d-flex justify-content-start text-hover-primary align-items-center icn" style="height: 75px; width: auto;">
                                    <div class="left d-flex justify-content-center align-items-center" style="width: 100px; height: 100%; background-color:#e1e1e1">
                                        <img src="{{asset('assets/images/file-icon')}}/${icon}" height="60" alt="">
                                    </div>
                                    <div class="right pl-2 pr-2">
                                        <div class="font-weight-bold text-muted">${media.url.split('-').pop()}</div>
                                    </div>
                                </a>
                            `).appendTo('.media-content');

                        })
                    })


                    showModal('modal-detail');
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
