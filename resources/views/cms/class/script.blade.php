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

            $(document).ready(function () {
                splide();
                AOS.init();
                selectPackage();
                TabDetailListener()
            });



            var TabDetailListener = ()=>{
                $('.tab-detail').click(function(event){
                    event.preventDefault()
                    let selected_tab = $(this).attr('href')
                    $(".tab-detail").removeClass("active" );
                    $("#tab-coach").find('content-tab-detail').replaceWith('content-tab-detail-selected')
                    $.blockUI({
                        css: { 
                            padding:        0, 
                            margin:         0, 
                            width:          '30%', 
                            top:            '25%', 
                            left:           '40%', 
                            textAlign:      'center', 
                            color:          '#000', 
                            border:         null, 
                            backgroundColor:null, 
                            cursor:         'wait' 
                        },
                        overlayCSS:  { 
                            backgroundColor: '#000', 
                            opacity:         0.7, 
                            cursor:          'wait' 
                        },
                        message: '<lottie-player src="https://assets3.lottiefiles.com/packages/lf20_o3kcs3sk.json" background="transparent" speed="1" style="width: 500px; height: 500px;" loop autoplay></lottie-player>' 
                    }); 
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

            splide = () => {
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
                new Splide('#class-category-splide', {
                    lazyLoad: true,
                    pagination: false,
                    type: 'loop',
                    breakpoints: {
                        640: {
                            perPage: 1,
                        },
                    }
                }).mount();
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