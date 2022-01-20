<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {

            $(document).ready(function () {
                @if(!$classroom_categories->isEmpty())
                    splide();
                @endif
                AOS.init();
                eventCategorySelectedListener()
                eventSubCategoryChangeListener()
                eventSearchListener()
                // $('.btn-tertiary').trigger( "click" );
            });

            var eventSearchListener = ()=>{
                $('#search').keyup(searchDelay(function(event) {
                    $.ajax({
                        url: `{{ url('video-course/search') }}`,
                        data:{
                            'search':$(this).val()
                        },
                        type: 'POST',
                    })
                    .done(function(res, xhr, meta) {
                        let element = "";
                        $.each(res.data, function(index, item){
                            element += `<option value="${item.name}" data-id="${item.id}">`
                        })
                        $("#datalistOptions").html(element)
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                       
                    });
                }, 1000));

                $("#search").on('input',function(event){
                    let video_course_id = $("#datalistOptions option[value='" + $(this).val() + "']").attr('data-id')
                    if(video_course_id)
                        window.location.href = `/video-course/${video_course_id}/detail`;
                })
            }

            var eventCategorySelectedListener = ()=>{
                $('.class-category-filter__wrapper').click(function(event){
                    $('.splide__slide div.class-category-selected').removeClass('class-category-selected');
                    $(this).addClass('class-category-selected');
                    let category_id = $(this).data("id")
                    getSubCategory(category_id)
                })
            }

            var eventSubCategoryChangeListener = ()=>{
                $('.btn-tertiary').click(function(event){
                    $('.btn-tertiary').removeClass('active');
                    $(this).addClass('active');
                    let sub_category_id = $(this).data("id")
                    getVideoCourse(sub_category_id)
                })
            }


            var getSubCategory = (category_id)=>{
                showLoader()
                $.ajax({
                    url: `{{ url('/classroom_category') }}/${category_id}/sub_classroom_category`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    $("#sub-category").html(res.data.sub_category_html)
                    $("#video_courses").html(res.data.video_courses_html)
                    $("#empty-video-course").html('')

                    if(!res.data.video_courses_html)
                        $("#empty-video-course").html(res.data.default_html)
                    eventSubCategoryChangeListener()
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                   hideLoader()
                });
            }

            var getVideoCourse = (sub_category_id)=>{
                showLoader()
                $.ajax({
                    url: `{{ url('/classroom_category/sub_classroom_category') }}/${sub_category_id}/video-course`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    $("#video_courses").html(res.data.video_courses_html)
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                   hideLoader()
                });
            }

            splide = () => {
                new Splide('#category-splide', {
                    type: 'loop',
                    arrow: false,
                    perPage: 5,
                    perMove: 2,
                    pagination: false,
                    padding: {
                        right: '0rem',
                        left: '0rem',
                    },
                    breakpoints: {
                        1025: {
                            perPage: 3,
                            padding: {
                                right: '0rem',
                                left: '0rem',
                            },
                        },
                        991: {
                            perPage: 2,
                            padding: {
                                right: '0rem',
                                left: '0rem',
                            },
                        },
                        640: {
                            perPage: 1,
                            padding: {
                                right: '3rem',
                                left: '3rem',
                            },
                        },
                    }
                }).mount();
            }
        };
        return {
            init: function () {
                _componentPage();
            }
        }
    }();
    document.addEventListener('DOMContentLoaded', function () {
        Page.init();
    });

</script>

