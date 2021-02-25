<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {

            $(document).ready(function () {
                splide();
                AOS.init();
            });

            splide = () => {
                new Splide('#gallery__splide', {
                    type: 'loop',
                    arrow: false,
                    perPage: 2,
                    pagination: false,
                    perMove: 1,
                    margin: {
                        right: '5rem',
                    },
                    breakpoints: {
                        991: {
                            perPage: 1,
                            padding: {
                                right: '3rem',
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