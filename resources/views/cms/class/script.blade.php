<script type="text/javascript">
    $('.btn-register-classroom').click(function () {
        $('#classRegisterModal').modal('show')
    });
    $('.btn-close').click(function () {
        $('.modal').modal('hide')
    });

    $(".addtocart").click(function () {
        $.ajax({
            url: "{{ url('student/class/add-to-cart') }}",
            type: "POST",
            data: $("#form-classrom").serialize()
        })
        .done(function(res, xhr, meta) {
            $(".cart-added").toggle();
            $(".addtocart").toggle();
            $(".cart-added").css("display", "flex");
        })
        .fail(function(res, error) {
            toastr.error('Internal Server Error', 'Failed')
        })
        .always(function() {
        });
    });

    $(".addMasterLessonToCart").click(function () {
        $.ajax({
            url: "{{ url('student/class/add-to-cart') }}",
            type: "POST",
            data: $("#form-master-lesson").serialize()
        })
        .done(function(res, xhr, meta) {
            $(".cart-added").toggle();
            $(".addMasterLessonToCart").toggle();
            $(".cart-added").css("display", "flex");
        })
        .fail(function(res, error) {
            toastr.error('Internal Server Error', 'Failed')
        })
        .always(function() {
        });
    });

    var showModalLoginRequired = ()=>{
        $("#loginRequiredModal").modal('show')
    }

    var showModalRegisterClassroom = (classroom_id)=>{
        $.ajax({
            url: `/student/class/${classroom_id}/detail`,
            type: 'GET',
        })
        .done(function(res, xhr, meta) {
            let nf = new Intl.NumberFormat();
            $("#form-classrom").find("input[name=classroom_id]").val(res.data.id)
            $("#classroom-name").text(res.data.name)
            $("#classroom-session").text(`${res.data.session_total} Sesi | @${res.data.session_duration}Menit`)
            $("#classroom-price").text(`Rp.${nf.format(res.data.price)}.00`)

            if(res.data.package_type == 1)
                $("#classroom-type").text('Special Class')
            else if(res.data.package_type == 2)
                $("#classroom-type").text('Regular Class')
            else
                $("#classroom-type").text('Master Lesson')

            $('#classRegisterModal').modal('show')
        })
        .fail(function(res, error) {
            toastr.error(res.responseJSON.message, 'Failed')
        })
        .always(function() {

        });
    }

    var showModalRegisterMasterLesson = (master_lession_id)=>{
        $.ajax({
            url: `/master-lesson/${master_lession_id}/detail`,
            type: 'GET',
        })
        .done(function(res, xhr, meta) {
            $("#form-master-lesson").find("input[name=master_lesson_id]").val(res.data.master_lesson.id)
            $("#master-lesson-name").text(res.data.master_lesson.name)
            $("#master-lesson-platform").text(res.data.master_lesson.platform)
            $("#master-lesson-price").text(res.data.master_lesson.price)
            $("#master-lesson-description").text(res.data.master_lesson.description)

            $('#masterLessonRegisterModal').modal('show')
        })
        .fail(function(res, error) {
            toastr.error(res.responseJSON.message, 'Failed')
        })
        .always(function() {

        });
    }


</script>

<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {
            var splide_sub_category;
            var splide_classroom;
            var splide_class;
            $(document).ready(function () {

                @if(!$regular_classrooms->isEmpty())
                    splide();
                @endif

                @if(!$classroom_categories->isEmpty())
                    splideCategory()
                @endif

                @if(!$sub_categories->isEmpty())
                    splideSubCategory()
                @endif


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
                showLoader()
                $.ajax({
                    url: `/class/${category_id}/sub_classroom_category/${sub_category_id}/package/${package}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    if(res.data.classroom_html){
                        $("#class-content").html(res.data.classroom_html)
                        TabDetailListener()
                        eventCategorySelectedListener()
                        eventSubCategoryChangeListener()
                        packageListener()
                        splide()
                    }else{
                        $("#class-content").html(`
                            <div class="col-12 pr-0 pr-lg-4 column-center">
                                <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
                                <h4 class="mt-3 text-center">Wah, Class belum tersedia</h4>
                            </div>`)
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                    hideLoader()
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

                    $("#classroom-content").html(res.data.classroom_html)
                    splideSubCategory()
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            }

            var getDescription = (classroom_id)=>{
                $.ajax({
                    url: `/class/${classroom_id}/description`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    splide_class.destroy()
                    $("#description").html(res.data.html)
                    splide()
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            }

            var getCoach = (classroom_id)=>{
                showLoader()
                $.ajax({
                    url: `/class/${classroom_id}/coachs`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    splide_class.destroy()
                    $("#description").html(res.data.html)
                    splide()
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                   hideLoader()
                });
            }

            var getTools = (classroom_id)=>{
                showLoader()
                $.ajax({
                    url: `/class/${classroom_id}/tools`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    splide_class.destroy()
                    $("#description").html(res.data.html)
                    splide()
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                   hideLoader()
                });
            }

            var getGuests = (master_lession_id)=>{
                showLoader()
                $.ajax({
                    url: `/master-lesson/${master_lession_id}/guest-star`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    splide_class.destroy()
                    $("#description").html(res.data.html)
                    splide()
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                    hideLoader()
                });
            }

            var getMasterLesson = (master_lession_id)=>{
                $.ajax({
                    url: `/master-lesson/${master_lession_id}/detail`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    splide_class.destroy()
                    $("#description").html(res.data.html)
                    splide()
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

                    let current_tab = $(this).attr('href')
                    let classroom_id = $(this).data('id')

                    if(current_tab == "tab-coach"){
                        getCoach(classroom_id)
                    }else if(current_tab == "tab-tools"){
                        getTools(classroom_id)
                    }else if(current_tab == "tab-description"){
                        getDescription(classroom_id)
                    }else if(current_tab == "tab-master-lession-description"){
                        getMasterLesson(classroom_id)
                    }else{
                        getGuests(classroom_id)
                    }

                    event.preventDefault()

                    // let selected_tab = $(this).attr('href')
                    // $(".tab-detail").removeClass("active" );
                    // $("#tab-coach").find('content-tab-detail').replaceWith('content-tab-detail-selected')
                    // event.preventDefault()

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

                splide_class = new Splide('#class-splide', {
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
