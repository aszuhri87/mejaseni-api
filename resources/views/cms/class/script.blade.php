<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script type="text/javascript">
    $('.registerNow').click(function () {
        $('#modalLoginNeeded').modal('show')
    });
    $('.btn-close').click(function () {
        $('.modal').modal('hide')
    });


</script>

<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {
            var splide_sub_category;
            var splide_classroom;
            $(document).ready(function () {
                splideCategory()
                splide();
                splideSubCategory()
                AOS.init();
                // selectPackage();


                TabDetailListener()
                eventCategorySelectedListener()
                eventSubCategoryChangeListener()
                packageListener()
            });

            var packageListener = ()=>{
                $('.package').click(function(event){
                    $('.package').removeClass('active');
                    $(this).addClass('active');

                    let package = $(this).data('id')
                    let selected_category = $('.class-category-selected').data('id')
                    let selected_sub_category = $('.btn-tertiary.active').data('id')
                    console.log(package)
                    getPackage(selected_category, selected_sub_category, package)
                    event.preventDefault()
                })
            }

            var eventCategorySelectedListener = ()=>{
                $('.class-category-filter__wrapper').click(function(event){
                    $('.splide__slide div.class-category-selected').removeClass('class-category-selected');
                    $(this).addClass('class-category-selected');
                    let category_id = $(this).data("id")
                    getSubCategory(category_id, this)
                })
            }

            var eventSubCategoryChangeListener = ()=>{
                $('.btn-tertiary').click(function(event){
                    $('.btn-tertiary').removeClass('active');
                    $(this).addClass('active');
                    let sub_category_id = $(this).data("id")
                    getClassroom(sub_category_id)
                })
            }

            var getPackage = (category_id, sub_category_id, package)=>{
                $.ajax({
                    url: `/class/${category_id}/sub_classroom_category/${sub_category_id}/package/${package}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    $("#classrooms").html(res.data.classroom_html)
                    splide()
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                   
                });
            }


            var getSubCategory = (category_id, element)=>{
                $.ajax({
                    url: `{{ url('/class/classroom_category') }}/${category_id}/sub_classroom_category`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    if(res.data.classroom_html){
                        $("#empty-classroom").html('')
                        $("#sub-category").html(res.data.sub_category_html)
                        $("#classroom-content").html(res.data.classroom_html)
                        splideSubCategory()
                        eventSubCategoryChangeListener()
                    }else{
                        $("#category-splide").css('visibility','none')
                        $("#sub-category").html('')
                        $("#classroom-content").html('')
                        $("#empty-classroom").html(res.data.default_html)
                        splide_sub_category.destroy()
                        console.log('destroy')
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                   
                });
            }

            var getClassroom = (sub_category_id)=>{
                $.ajax({
                    url: `{{ url('/classroom_category/sub_classroom_category') }}/${sub_category_id}/classroom`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    console.log(res)
                    $("#classroom-content").html(res.data.classroom_html)
                    splideSubCategory()
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                   
                });
            }

            


            var TabDetailListener = ()=>{
                $('.tab-detail').click(function(event){
                    $('.tab-detail').removeClass('active');
                    $(this).addClass('active');
                    event.preventDefault()
                    
                    // let selected_tab = $(this).attr('href')
                    // $(".tab-detail").removeClass("active" );
                    // $("#tab-coach").find('content-tab-detail').replaceWith('content-tab-detail-selected')
                    // event.preventDefault()
                    // $.blockUI({
                    //     css: { 
                    //         padding:        0, 
                    //         margin:         0, 
                    //         width:          '30%', 
                    //         top:            '25%', 
                    //         left:           '40%', 
                    //         textAlign:      'center', 
                    //         color:          '#000', 
                    //         border:         null, 
                    //         backgroundColor:null, 
                    //         cursor:         'wait' 
                    //     },
                    //     overlayCSS:  { 
                    //         backgroundColor: '#000', 
                    //         opacity:         0.7, 
                    //         cursor:          'wait' 
                    //     },
                    //     message: '<lottie-player src="https://assets3.lottiefiles.com/packages/lf20_o3kcs3sk.json" background="transparent" speed="1" style="width: 500px; height: 500px;" loop autoplay></lottie-player>' 
                    // }); 
            // test();
                    // $("#tab-coach").removeClass("content-tab-detail");
                    // $("#tab-coach").addClass("content-tab-detail-selected")
                    // $(".content-tab-detail").css("display","none");
                    // $("#tab-coach .content-tab-detail").css('display','block');
                    // $(this).addClass( "active" )
                    
                })
            }

            var selectPackage = ()=>{
                $('.package').click(function(e){
                    e.preventDefault()
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                    })
                    .done(function(res, xhr, meta) {
                        toastr.success(res.message, 'Success')
                        init_table.draw(false);
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() { })
                })
            }

            var splideCategory = ()=>{
                new Splide('#category-splide', {
                    type: 'loop',
                    arrow: false,
                    perPage: 5,
                    perMove: 1,
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

            var splideSubCategory = ()=>{
                splide_sub_category = new Splide('#class-category-splide', {
                    lazyLoad: true,
                    pagination: false,
                    type: 'loop',
                    breakpoints: {
                        640: {
                            perPage: 1,
                        },
                    }
                }).mount();
            }

            splide = () => {
                
                new Splide('#class-splide', {
                    lazyLoad: true,
                    perPage: 1,
                    type: 'loop',
                    breakpoints: {
                        640: {
                            perPage: 1,
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