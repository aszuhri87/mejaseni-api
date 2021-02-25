<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {

            $(document).ready(function () {
                splide();
                AOS.init();
            });

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