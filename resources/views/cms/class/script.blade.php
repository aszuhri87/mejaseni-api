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

                AOS.init();
                TabDetailListener()

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-tertiary',
                        cancelButton: 'btn btn-tertiary ml-3'
                    },
                    buttonsStyling: false
                })


                //event
                $('.package').click(function(event){
                    $('.package').removeClass('active');
                    $(this).addClass('active');

                    let package = $(this).data('id')
                    let selected_category = $('.class-category-selected').data('id')
                    let selected_sub_category = $('.btn-tertiary.active').data('id')

                    if(!selected_category){
                        swalWithBootstrapButtons.fire({
                            title: 'Gagal!',
                            padding: '2em',
                            icon: 'danger',
                            text: "Kategori belum dipilih"
                        })
                        return true
                    }

                    getPackage(selected_category, selected_sub_category, package)
                    event.preventDefault()
                })

                //classroom
                $('.class-category-filter__wrapper').click(function(event){
                    $('.splide__slide div.class-category-selected').removeClass('class-category-selected');
                    $(this).addClass('class-category-selected');
                    let package = $('.package.active').data('id')
                    let selected_category = $('.class-category-selected').data('id')
                    let selected_sub_category = undefined
                    getPackage(selected_category, selected_sub_category, package)

                    event.preventDefault()
                })

                initSubCategory()
                initReadMore()
            });

            var initSubCategory = ()=>{
                $('.btn-tertiary').click(function(event){
                    $('.btn-tertiary').removeClass('active');
                    $(this).addClass('active');
                    let selected_sub_category = $(this).data("id")
                    let package = $('.package.active').data('id')
                    let selected_category = $('.class-category-selected').data('id')

                    getPackage(selected_category, selected_sub_category, package)
                    event.preventDefault()
                })
            }

            var initReadMore = ()=>{
                $('.readmore').readmore({
                  speed: 20,
                  moreLink: '<a  class="font-weight-bold">Selanjutnya...</a>',
                  lessLink: '<a  class="font-weight-bold">Tutup</a>',
                  collapsedHeight: 150,
                });

            }


            var getPackage = (category_id, sub_category_id, package)=>{
                showLoader()
                $.ajax({
                    url: `/class/${category_id}/sub_classroom_category/${sub_category_id}/package/${package}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    $("#sub-category").html(res.data.sub_category_html)
                    initSubCategory()
                    if(res.data.classroom_html){
                        $("#empty-classroom").html('')
                        $("#class-content").html(res.data.classroom_html)
                        $("#classroom-content").html(res.data.classroom_review_html)
                        TabDetailListener()
                        splide()
                        initReadMore()
                    }else{
                        $("#class-content").html(`
                            <div class="col-12 pr-0 pr-lg-4 column-center">
                            <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
                            <h4 class="mt-3 text-center">Wah, Class belum tersedia</h4>
                            </div>`)

                        $("#classroom-content").html(`
                            <div class="mb-5 empty-store">
                            <div class="row my-5 py-5">
                            <div class="col-12 pr-0 pr-lg-4 column-center">
                            <img style="width: 200px;" src="/cms/assets/img/svg/empty-store.svg" alt="">
                            <h4 class="mt-3 text-center">Wah, video course yang kamu cari <br />belum dibuat nih</h4>
                            </div>
                            </div>
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

            var getDescription = (classroom_id)=>{
                $.ajax({
                    url: `/class/${classroom_id}/description`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    splide_class.destroy()
                    $("#description").html(res.data.html)
                    splide()
                    initReadMore()
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

            splide = () => {
                splide_class = new Splide('#class-splide', {
                    pagination: false,
                    lazyLoad: true,
                    perPage: 1,
                    type: 'loop',
                    arrows:'',
                    breakpoints: {
                        640: {
                            perPage: 1,
                        },
                    }
                }).mount();


                if(splide_sub_category){
                    splide_sub_category.destroy()
                }
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

                splide_sub_category.on( 'arrows:updated', function(index) {
                    splide_class.go(splide_sub_category.index)
                });

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
