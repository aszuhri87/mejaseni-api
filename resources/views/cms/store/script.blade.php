<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {

            $(document).ready(function () {
                AOS.init();
                splide()
            });

            splide = () => {
                new Splide('#store-banner-splide', {
                    lazyLoad: true,
                    autoplay: true,
                    type: 'loop',
                    breakpoints: {
                      640: {
                        perPage: 2,
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

