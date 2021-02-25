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
            });

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