<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){

            $(document).ready(function() {
                formSubmit();
                initAction();
                getSubClassroomCategory();
                getVideo();
            });

            const initAction = () => {
                $(document).on('change','#filter',function(event){
                    event.preventDefault();
                    KTApp.blockPage({
                        overlayColor: '#000000',
                        state: 'primary',
                        opacity: 0.5,
                        message: 'Processing...'
                    });
                    getVideo($(this).val())
                });
            },
            formSubmit = () => {
                $('#form-invoice').submit(function(event){
                    event.preventDefault();

                    btn_loading('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        toastr.success(res.message, 'Success')
                        init_table.draw(false);
                        hideModal('modal-invoice');
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
            },
            getSubClassroomCategory = () => {
                $.ajax({
                    url: `{{ url('student/my-video/get-sub-classroom-category') }}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    element = `<option value="">Pilih Sub Kategori Kelas</option>`;

                    $.each(res.data, function(index, data){
                        element += `<option value="${data.sub_classroom_category_id}">${data.name}</option>`;
                    });

                    $('#filter').html(element);
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            getVideo = (sub_classroom_category) => {
                $.ajax({
                    url: `{{ url('student/my-video/get-video') }}`,
                    type: `GET`,
                    data: {
                        sub_classroom_category_id:sub_classroom_category
                    }
                })
                .done(function(res, xhr, meta) {
                    if (res.status == 200) {
                        let element = ``;
                        if(res.data.length > 0 ){
                            $.each(res.data, function(index, data) {
                                element += `
                                <div class="card mb-4">
                                    <div width="100%" class="p-5">
                                        <img style="float: left !important;" src="${data.image_url}" class="rounded" width="20%" height="150px">
                                        <div class="row">
                                            <div class="col">
                                                <a href="{{url('student/my-video/video-detail')}}/${data.session_video_id}" style="color: black;">
                                                    <h4>${data.name}</h4>
                                                </a>
                                                <p>${data.description}</p>
                                                <p>Mentored by ${data.coach_name}</p>
                                                <p>
                                                    <a href="{{url('student/my-video/video-detail')}}/${data.session_video_id}" class="btn btn-primary">
                                                        <span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                <path d="M9.82866499,18.2771971 L16.5693679,12.3976203 C16.7774696,12.2161036 16.7990211,11.9002555 16.6175044,11.6921539 C16.6029128,11.6754252 16.5872233,11.6596867 16.5705402,11.6450431 L9.82983723,5.72838979 C9.62230202,5.54622572 9.30638833,5.56679309 9.12422426,5.7743283 C9.04415337,5.86555116 9,5.98278612 9,6.10416552 L9,17.9003957 C9,18.1765381 9.22385763,18.4003957 9.5,18.4003957 C9.62084305,18.4003957 9.73759731,18.3566309 9.82866499,18.2771971 Z" fill="#000000"/>
                                                            </g>
                                                        </svg></span>
                                                        Lihat Video
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                            });
                        }else{
                            element += `
                                <div class="card">
                                    <div class="card-body text-muted text-center">
                                        <h4>Video Not Available</h4>
                                    </div>
                                </div>
                            `;
                        }
                        $('.video').html(element);
                        KTApp.unblockPage();
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

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
